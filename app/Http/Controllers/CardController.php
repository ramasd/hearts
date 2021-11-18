<?php

namespace App\Http\Controllers;

class CardController extends Controller
{
    public function index($number, $table)
    {
        $playedCards = [];
        $url = "https://boardgamearena.com/$number/hearts/hearts/notificationHistory.html?table=$table&from=1&privateinc=1&history=1";
        $json = file_get_contents($url);
        $arr = json_decode($json, true);

        $records = array_reverse($arr['data']['data']);

        foreach ($records as $record) {
            foreach ($record['data'] as $data) {

                if ($data['type'] === "playCard") {
                    $playedCards[] = $data['args']['color_displayed'] . "_" . $data['args']['value_displayed'];
                }

                if ($data['type'] === "newScores") {
                    array_pop($playedCards);
                    break 2;
                }
            }
        }

        return view('cards', [
            'playedCards' => $playedCards,
            'cards' => config("constants.CARDS"),
        ]);
    }
}
