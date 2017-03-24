<?php

$container = require __DIR__ . '/bootstrap.php';

$data = [
    'measurements'  => [
        [
            'type'              => "A380",
            'noise-level'       => 103,
            'brake-distance'    => 2130,
            'vibrations'        => 0.81,
        ],
        [
            'type'              => "A380",
            'noise-level'       => 101,
            'brake-distance'    => 2070,
            'vibrations'        => 0.88,
        ],
        [
            'type'              => "737",
            'noise-level'       => 94,
            'brake-distance'    => 1730,
            'vibrations'        => 0.82,
        ],
        [
            'type'              => "737",
            'noise-level'       => 96,
            'brake-distance'    => 1820,
            'vibrations'        => 0.79,
        ],
    ],
    'samples'   => [
        [
            'id'                => 1,
            'noise-level'       => 102,
            'brake-distance'    => 2105,
            'vibrations'        => 0.80,
        ],
        [
            'id'                => 2,
            'noise-level'       => 97,
            'brake-distance'    => 1830,
            'vibrations'        => 0.80,
        ],
    ],
];

$data_string = Nette\Utils\Json::encode($data);

$ch = curl_init('http://hovada.tomasgrasl.cz/task2/input');
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Content-Length: ' . strlen($data_string)]
);

$result = curl_exec($ch);
echo $result;
