<?php

namespace Jowy\RabbitInternet\Services;

use Jowy\RabbitInternet\Contracts\TwitterService;

/**
 * Class Twitter
 * @package Jowy\RabbitInternet\Services
 */
class Twitter implements TwitterService
{
    /**
     * @var \TwitterAPIExchange
     */
    private $client;

    private $searchUrl = 'https://api.twitter.com/1.1/search/tweets.json';

    /**
     * @param \TwitterAPIExchange $client
     */
    public function __construct(\TwitterAPIExchange $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $keyword
     * @param float $latitude
     * @param float $longitude
     * @param string $radius
     * @return array
     * @throws \Exception
     */
    public function searchByKeywordAndGeoLocation($keyword, $latitude, $longitude, $radius)
    {
        $response = $this->client->setGetfield("?q={$keyword}&geocode={$latitude},{$longitude},{$radius}")
            ->buildOauth($this->searchUrl, 'GET')
            ->performRequest();

        return json_decode($response);
    }
}
