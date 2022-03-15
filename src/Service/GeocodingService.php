<?php

namespace App\Service;

use JetBrains\PhpStorm\ArrayShape;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GeocodingService
{
    public function __construct(private HttpClientInterface $client){}

    #[ArrayShape(['latitude' => "mixed", 'longitude' => "mixed"])]
    public function getCoordinatesFromCityName(string $cityName)
    {
        $response = $this->client->request(
            'GET',
            'https://nominatim.openstreetmap.org/search',
            [
                'query' => [
                    'q' => $cityName,
                    'format' => 'json'
                ]
            ]
        );
        $results = $response->toArray();

        if (!isset($results[0])) {
            throw new \Exception("No coordinates found for city $cityName");
        }

        return [
            'latitude' => $results[0]['lat'],
            'longitude' => $results[0]['lon']
        ];
    }
}