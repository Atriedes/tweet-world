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

    public function searchByGeoLocation($latitude, $longitude, $radius)
    {
        $response = $this->client->setGetfield("?geocode={$latitude},{$longitude},{$radius}")
            ->buildOauth($this->searchUrl, 'GET')
            ->performRequest();

        return json_decode($response);
    }

    public function searchByKeyword($keyword)
    {
        $response = $this->client->setGetfield("?q={$keyword}")
            ->buildOauth($this->searchUrl, 'GET')
            ->performRequest();

        return json_decode($response);
    }

    /**
     * @param $keyword
     * @param $latitude
     * @param $longitude
     * @param $radius
     * @return mixed
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
