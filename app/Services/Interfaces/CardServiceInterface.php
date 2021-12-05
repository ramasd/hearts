<?php

namespace App\Services\Interfaces;

/**
 * Interface CardServiceInterface
 * @package App\Services\Interfaces
 */
interface CardServiceInterface
{
    /**
     * @param $url
     * @return bool|string
     */
    public function fileGetContentsCurl($url);

    /**
     * @param $url
     * @return mixed|string
     */
    public function getNumber($url);

    /**
     * @param $url
     * @return mixed|string|void
     */
    public function getTableId($url);

    /**
     * @param $url
     * @return mixed
     */
    public function getNewestRecords($url);

    /**
     * @param array $records
     * @param int $points
     * @param array $playerPoints
     * @param array $playedCards
     * @return array
     */
    public function calculateStatistics(array $records, int $points = 0, array $playerPoints = [], array $playedCards = []): array;

    /**
     * @param $content
     * @return array
     */
    public function getPlayerColors($content): array;
}

