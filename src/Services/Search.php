<?php

namespace Jowy\RabbitInternet\Services;

use Jowy\RabbitInternet\Contracts\Storage;
use Jowy\RabbitInternet\Contracts\TwitterService;

/**
 * Class Search
 * @package Jowy\RabbitInternet\Services
 */
class Search
{
    /**
     * @var Storage
     */
    private $storage;

    /**
     * @var TwitterService
     */
    private $twitter;

    /**
     * @var int
     */
    private $cacheTtl;

    /**
     * @param Storage $storage
     * @param TwitterService $twitter
     * @param int $cacheTtl
     */
    public function __construct(Storage $storage, TwitterService $twitter, $cacheTtl = 3600)
    {
        $this->storage = $storage;
        $this->twitter = $twitter;
        $this->cacheTtl = $cacheTtl;
    }

    /**
     * @param string $keyword
     * @param string$latitude
     * @param string $longitude
     * @param string$radius
     * @param string $userId
     * @return array
     */
    public function search($keyword, $latitude, $longitude, $radius, $userId)
    {
        $this->storage->addUserHistory($userId, $keyword);

        /**
         * get result from cache if any
         */
        if ($cache = $this->getResultFromCache($keyword)) {
            return $cache;
        }

        /**
         * search from twitter
         */
        $result = $this->twitter->searchByKeywordAndGeoLocation($keyword, $latitude, $longitude, $radius);

        $locations = [];
        $statuses = [];

        /**
         * filter tweet which contain geo field only
         */
        foreach ($result->statuses as $tweet) {
            if ($tweet->geo !== null) {
                $locations[] = [
                    "{$tweet->user->profile_image_url}",
                    $tweet->geo->coordinates[0],
                    $tweet->geo->coordinates[1],
                ];
                $statuses[] = [
                    "<div>{$tweet->text} - $tweet->created_at</div>"
                ];
            }
        }

        $cache = [
            'locations' => $locations,
            'statuses' => $statuses
        ];

        /**
         * save to database
         */
        $this->storage->cacheSearchResult($keyword, $cache);

        return $cache;
    }

    /**
     * @param $keyword
     * @return bool|array
     */
    private function getResultFromCache($keyword)
    {
        /**
         * return immediately if no cache found
         */
        $cache = $this->storage->getSearchResultCache($keyword);
        if (count($cache) === 0) {
            return false;
        }

        $diff = strtotime(date('Y-m-d H:i:s')) - strtotime($cache[0]['time']);

        /**
         * check cache if expired
         */
        if ($diff <= $this->cacheTtl) {
            return unserialize($cache[0]['cache']);
        }

        return false;
    }
}
