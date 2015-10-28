<?php

namespace Jowy\RabbitInternet\Services;

use Doctrine\DBAL\Connection;
use Jowy\RabbitInternet\Contracts\Storage;

/**
 * Class SqliteStorage
 * @package Jowy\RabbitInternet\Services
 */
class SqliteStorage implements Storage
{
    /**
     * @var Connection
     */
    private $db;

    /**
     * @param Connection $db
     */
    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    /**
     * @param string $userId
     * @param string $location
     */
    public function addUserHistory($userId, $location)
    {
        $history = $this->db->createQueryBuilder()
            ->select('*')
            ->from('user_history', 'u')
            ->where('u.user_id = :id')
            ->where('u.search_history = :location')
            ->setParameter('id', $userId)
            ->setParameter('location', strtolower($location))
            ->execute()
            ->fetchAll();

        if (count($history) === 0) {
            $this->db->insert(
                'user_history',
                ['user_id' => $userId, 'search_history' => strtolower($location), 'time' => date('Y-m-d H:i:s')]
            );
        }

        /**
         * update timestamp only if location exist
         */
        $this->db->update(
            'user_history',
            [
                'time' => date('Y-m-d H:i:s')
            ],
            [
                'user_id' => $userId,
                'search_history' => strtolower($location)
            ]
        );

    }

    /**
     * @param string $userId
     * @return array
     */
    public function getUserHistory($userId)
    {
        $qb = $this->db->createQueryBuilder()
            ->select('*')
            ->from('user_history', 'u')
            ->where('u.user_id = :id')
            ->orderBy('u.time', 'DESC')
            ->setParameter('id', $userId)
            ->execute();

        return $qb->fetchAll();
    }

    /**
     * @param string $location
     * @param array $data
     */
    public function cacheSearchResult($location, $data)
    {
        $qb = $this->db->createQueryBuilder()
            ->select('*')
            ->from('search_cache', 's')
            ->where('s.location = :location')
            ->setParameter('location', strtolower($location))
            ->execute();

        if (count($qb->fetchAll()) === 0) {
            $this->db->insert(
                'search_cache',
                [
                    'location' => strtolower($location),
                    'cache' => serialize($data),
                    'time' => date('Y-m-d H:i:s')
                ]
            );
            return;
        }

        $this->db->update(
            'search_cache',
            [
                'cache' => serialize($data),
                'time' => date('Y-m-d H:i:s')
            ],
            [
                'location'  => strtolower($location)
            ]
        );
    }

    /**
     * @param string $location
     * @return array
     */
    public function getSearchResultCache($location)
    {
        $qb = $this->db->createQueryBuilder()
            ->select('*')
            ->from('search_cache', 's')
            ->where('s.location = :location')
            ->setParameter('location', strtolower($location))
            ->execute();

        return $qb->fetchAll();
    }
}
