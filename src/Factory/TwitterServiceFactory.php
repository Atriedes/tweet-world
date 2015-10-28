<?php

namespace Jowy\RabbitInternet\Factory;

use Jowy\RabbitInternet\Contracts\Factory;
use Jowy\RabbitInternet\Services\Twitter;

/**
 * Class TweetServiceFactory
 * @package Jowy\RabbitInternet\Factory
 */
class TwitterServiceFactory implements Factory
{
    /**
     * @var \TwitterAPIExchange
     */
    private $tweetClient;

    /**
     * @param \TwitterAPIExchange $tweetClient
     */
    public function __construct(\TwitterAPIExchange $tweetClient)
    {
        $this->tweetClient = $tweetClient;
    }

    /**
     * @param array $options
     * @return Twitter
     */
    public function create(array $options = [])
    {
        return new Twitter($this->tweetClient);
    }
}
