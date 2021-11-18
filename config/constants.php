<?php

$const = [
    'suits' => ['heart', 'diamond', 'spade', 'club'],
    'types' => ['2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K', 'A'],
];

return [
    'SUITS' => $const["suits"],
    'TYPES' => $const["types"],
    'CARDS' => array_fill_keys($const["suits"], $const["types"]),
];
