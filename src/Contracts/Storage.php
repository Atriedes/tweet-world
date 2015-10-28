<?php

namespace Jowy\RabbitInternet\Contracts;

/**
 * Interface Storage
 * @package Jowy\RabbitInternet\Contracts
 */
interface Storage
{
    public function getUserHistory($userId);

    public function addUserHistory($userId, $location);

    public function getSearchResultCache($location);

    public function cacheSearchResult($location, $data);
}
