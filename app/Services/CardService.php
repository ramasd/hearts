<?php

namespace App\Services;

use App\Repositories\Interfaces\CardRepositoryInterface;
use App\Services\Interfaces\CardServiceInterface;

class CardService implements CardServiceInterface
{
    /**
     * @var CardRepositoryInterface
     */
    protected $cardRepository;

    /**
     * CardService constructor.
     *
     * @param CardRepositoryInterface $cardRepository
     */
    public function __construct(CardRepositoryInterface $cardRepository)
    {
        $this->cardRepository = $cardRepository;
    }

    /**
     * @param $url
     * @return bool|string
     */
    public function fileGetContentsCurl($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $content = curl_exec($ch);
        curl_close($ch);

        return $content;
    }

    /**
     * @param $url
     * @return mixed|string
     */
    public function getNumber($url)
    {
        $path = trim(parse_url($url, PHP_URL_PATH), '/');
        $urlSegments = explode('/', $path);

        return $urlSegments[0];
    }

    /**
     * @param $url
     * @return mixed|string|void
     */
    public function getTableId($url)
    {
        $parts = parse_url($url);

        if (!isset($parts['query'])) {
            return null;
        }

        parse_str($parts['query'], $query);

        return $query['table'];
    }

    /**
     * @param $url
     * @return mixed
     */
    public function getNewestRecords($url)
    {
        $json = file_get_contents($url);
        $arr = json_decode($json, true);
        $records = $arr['data']['data'];
        $reversedRecords = array_reverse($records);
        $index = count($reversedRecords);

        foreach ($reversedRecords as $key => $record)
        {
            $containsNewScores = in_array('newScores', array_column($record['data'], 'type'));

            if ($containsNewScores) {
                $index = $key;
                break;
            }
        }

        return array_slice($records, count($records) - $index);
    }

    /**
     * @param array $records
     * @param int $points
     * @param array $playerPoints
     * @param array $playedCards
     * @return array
     */
    public function calculateStatistics(array $records, int $points = 0, array $playerPoints = [], array $playedCards = []): array
    {
        $playedCardsAmount = config("constants.INITIAL_PLAYED_CARDS_AMOUNT");

        foreach ($records as $record) {
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

        return [
            'points'                => $points,
            'player_points'         => $playerPoints,
            'played_cards'          => $playedCards,
            'played_cards_amount'   => $playedCardsAmount
        ];
    }

    /**
     * @param $content
     * @return array
     */
    public function getPlayerColors($content): array
    {
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
}
