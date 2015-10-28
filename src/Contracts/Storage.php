<?php

namespace Jowy\RabbitInternet\Contracts;

/**
 * Interface Storage
 * @package Jowy\RabbitInternet\Contracts
 */
interface Storage
{
    /**
     * @param $userId
     * @return array
     */
    public function getUserHistory($userId);

    /**
     * @param string $userId
     * @param string $location
     */
    public function addUserHistory($userId, $location);

    /**
     * @param  string $location
     * @return array
     */
    public function getSearchResultCache($location);

    /**
     * @param string $location
     * @param array $data
     */
    public function cacheSearchResult($location, $data);
}
