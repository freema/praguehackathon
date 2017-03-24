<?php

$container = require __DIR__ . '/bootstrap.php';

$data = [
    "citiesCount" => 4,
    "costOffers" => [
            [
            "from" => 0,
            "to" => 1,
            "price" => 6,
        ],
            [
            "from" => 1,
            "to" => 2,
            "price" => 10,
        ],
            [
            "from" => 2,
            "to" => 1,
            "price" => 10,
        ],
            [
            "from" => 1,
            "to" => 3,
            "price" => 12,
        ],
            [
            "from" => 3,
            "to" => 2,
            "price" => 8,
        ],
            [
            "from" => 3,
            "to" => 0,
            "price" => 1,
        ],
    ]
];
$data_string = Nette\Utils\Json::encode($data);

$ch = curl_init('http://hovada.tomasgrasl.cz/task1/input');
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Content-Length: ' . strlen($data_string)]
);

$result = curl_exec($ch);
echo $result;
