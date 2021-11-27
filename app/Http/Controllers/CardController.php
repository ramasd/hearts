<?php

namespace App\Http\Controllers;

class CardController extends Controller
{
    public function fileGetContentsCurl($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $html = curl_exec($ch);
        curl_close($ch);

        return $html;
    }

    public function getNicknameColors($number, $table)
    {
        if (!$content = $this->fileGetContentsCurl("https://boardgamearena.com/$number/hearts?table=$table")) {
            return false;
        }

        $dom = new \DOMDocument();
        @$dom->loadHtml($content);
        $xpath = new \DOMXPath($dom);
        $playerTables = $xpath->query("//*[@id='playertables']//div[contains(@class, 'playertable') and contains(@class, 'whiteblock') and contains(@class,'playertable_')]");

        $nicknameColors = [];
        $style = null;
        $color = null;

        if ($playerTables) {
            foreach ($playerTables as $playerTable) {
                $DOMAttributes = $playerTable->firstElementChild->attributes;

                foreach ($DOMAttributes as $DOMAttribute) {
                    if ($DOMAttribute->name === "style") {
                        $style = $DOMAttribute->value;
                    }
                }

                $attributes = array_map(function ($v) {return explode(':', $v);}, explode(';', $style));

                foreach ($attributes as $attribute) {
                    if ($attribute[0] === "color") {
                        $color = $attribute[1];
                    }
                }

                $nickname = trim($playerTable->firstElementChild->childNodes[0]->nodeValue);
                $nicknameColors[$nickname] = $color;
            }
        }

        return $nicknameColors;
    }

    public function index($number, $table)
    {
        if (!$nicknameColors = $this->getNicknameColors($number, $table)) {
            return "Wrong URL...";
        }

        $url = "https://boardgamearena.com/$number/hearts/hearts/notificationHistory.html?table=$table&from=1&privateinc=1&history=1";
        $json = file_get_contents($url);

        $arr = json_decode($json, true);
        $reversedRecords = array_reverse($arr['data']['data']);
        $index = count($reversedRecords);
        $playedCards = [];

        foreach ($reversedRecords as $key => $record)
        {
            $containsNewScores = in_array('newScores', array_column($record['data'], 'type'));

            if ($containsNewScores) {
                $index = $key;
                break;
            }
        }

        $records = array_reverse($reversedRecords);
        $newestRecords = array_slice($records, count($records) - $index);
        $points = 0;
        $playerPoints = [];
        $playedCardsAmount = config("constants.PLAYED_CARDS_AMOUNT");

        foreach ($newestRecords as $record) {
            if (isset($record['data'])) {
                foreach ($record['data'] as $data) {
                    if ($data['type'] === "playCard") {
                        $playedCards[$data['args']['color_displayed'] . "_" . $data['args']['value_displayed']] = $data['args']['player_name'];

                        if ($data['args']['color_displayed'] === "heart") {
                            $playedCardsAmount['heart']++;
                            $points++;
                        }

                        if ($data['args']['color_displayed'] === "diamond") {
                            $playedCardsAmount['diamond']++;
                        }

                        if ($data['args']['color_displayed'] === "spade") {
                            $playedCardsAmount['spade']++;
                            if ($data['args']['value_displayed'] === "Q") {
                                $points += 13;
                            }
                        }

                        if ($data['args']['color_displayed'] === "club") {
                            $playedCardsAmount['club']++;
                        }
                    }

                    if ($data['type'] === "trickWin") {
                        if (array_key_exists($data['args']['player_name'], $playerPoints)) {
                            $playerPoints[$data['args']['player_name']] = $playerPoints[$data['args']['player_name']] + $points;
                        } else {
                            $playerPoints[$data['args']['player_name']] = $points;
                        }
                        $points = 0;
                    }
                }
            }
        }

        return view('cards', [
            'playedCards' => $playedCards,
            'cards' => config("constants.CARDS"),
            'playerPoints' => $playerPoints,
            'playedCardsAmount' => $playedCardsAmount,
            'nicknameColors' => $nicknameColors
        ]);
    }
}
