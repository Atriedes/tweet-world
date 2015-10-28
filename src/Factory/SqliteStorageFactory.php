<?php

namespace Jowy\RabbitInternet\Factory;

use Jowy\RabbitInternet\Contracts\Factory;
use Jowy\RabbitInternet\Services\SqliteStorage;
use Silex\Application;

/**
 * Class SqliteStorageFactory
 * @package Jowy\RabbitInternet\Factory
 */
class SqliteStorageFactory implements Factory
{
    /**
     * @var Application
     */
    private $app;

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @param array $options
     * @return SqliteStorage
     */
    public function create(array $options = [])
    {
        return new SqliteStorage($this->app['db'], $this->app['settings']['cache_ttl']);
    }
}
