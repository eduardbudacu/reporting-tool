<?php

namespace Domain\DataSources;

use Exception;
use GuzzleHttp\Client;

class Api extends DataSource
{
    protected $client;

    protected $endpoints = [
        'brands.json' => '619fd5110ddbee6f8b11df27',
        'gmv.json' => '619fd5780ddbee6f8b11df45'
    ];

    public function __construct()
    {
        if (file_exists('credentials/jsonbin.json')) {
            $secrets = json_decode(file_get_contents('credentials/jsonbin.json'), true);
            $this->client = new Client([
                'base_uri' => 'https://api.jsonbin.io/v3/b/',
                'headers' => [
                    'X-Master-Key' => $secrets['key'],
                    'Content-Type' => 'application/json',
                    'X-Bin-Meta' => 'false'
                ]
            ]);
        } else {
            throw new Exception('invalid credentials file');
        }
    }

    public function read($filename)
    {
        $response = $this->client->request('GET', $this->endpoints[$filename]);
        if ($response->getStatusCode() == 200) {
            $body = $response->getBody();
            return json_decode((string) $body, true);
        } else {
            throw new Exception('Unable to read endpoint data');
        }
    }
}
