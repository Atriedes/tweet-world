<?php

namespace Jowy\RabbitInternet\Services;

use Jowy\RabbitInternet\Contracts\Geocode;

/**
 * Class GoogleGeocode
 * @package Jowy\RabbitInternet\Services
 */
class GoogleGeocode implements Geocode
{
    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var string
     */
    private $url = 'https://maps.googleapis.com/maps/api/geocode/json?';

    /**
     * @param string $apiKey
     */
    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @param string $city
     * @return array
     */
    public function searchLocationByCity($city)
    {
        $query = "address={$city}&key={$this->apiKey}";

        $response = json_decode(file_get_contents($this->url . $query), true);

        $results = [];
        if (array_key_exists('results', $response)) {
            $results = $response['results'];
        }

        return $results;
    }
}
