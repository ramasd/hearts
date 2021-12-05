<?php

namespace App\Repositories;

use App\Models\Card;
use App\Repositories\Interfaces\CardRepositoryInterface;

class CardRepository extends BaseRepository implements CardRepositoryInterface
{
    /**
     * CardRepository constructor.
     *
     * @param Card $card
     */
    public function __construct(Card $card)
    {
        parent::__construct($card);
    }

    public function index()
    {
        // TODO: Implement index() method.
    }
}
