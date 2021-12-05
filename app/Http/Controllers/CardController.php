<?php

namespace App\Http\Controllers;

use App\Services\Interfaces\CardServiceInterface;

class CardController extends Controller
{
    private $cardService;

    public function __construct(CardServiceInterface $cardService)
    {
        $this->cardService = $cardService;
    }

    public function index()
    {
        if(!isset($_GET['url']) or !$_GET['url']) {
            return view('url');
        }

        $url = $_GET['url'];

        if (!$content = $this->cardService->fileGetContentsCurl($url)) {
            return view('url')->with(['error' => 'Wrong URL...']);
        }

        $number = $this->cardService->getNumber($url);
        $tableId = $this->cardService->getTableId($url);
        $url = "https://boardgamearena.com/$number/hearts/hearts/notificationHistory.html?table=$tableId&from=1&privateinc=1&history=1";
        $newestRecords = $this->cardService->getNewestRecords($url);
        $statistics = $this->cardService->calculateStatistics($newestRecords);
        $playerColors = $this->cardService->getPlayerColors($content);

        return view('cards', ['statistics' => $statistics, 'playerColors' => $playerColors]);
    }
}
