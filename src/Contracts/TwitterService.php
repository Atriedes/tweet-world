<?php

namespace Jowy\RabbitInternet\Contracts;

/**
 * Interface TweetService
 * @package Jowy\RabbitInternet\Contracts
 */
interface TwitterService
{
    public function searchByKeyword($keyword);

    public function searchByGeoLocation($latitude, $longitude, $radius);

    public function searchByKeywordAndGeoLocation($keyword, $latitude, $longitude, $radius);
}
