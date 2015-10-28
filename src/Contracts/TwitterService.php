<?php

namespace Jowy\RabbitInternet\Contracts;

/**
 * Interface TweetService
 * @package Jowy\RabbitInternet\Contracts
 */
interface TwitterService
{
    /**
     * @param string $keyword
     * @param float $latitude
     * @param float $longitude
     * @param string $radius
     * @return array
     */
    public function searchByKeywordAndGeoLocation($keyword, $latitude, $longitude, $radius);
}
