<?php

namespace Jowy\RabbitInternet\Factory;

use Jowy\RabbitInternet\Contracts\Factory;
use Jowy\RabbitInternet\Services\GoogleGeocode;

/**
 * Class GoogleGeocodeServiceFactory
 * @package Jowy\RabbitInternet\Factory
 */
class GoogleGeocodeServiceFactory implements Factory
{
    /**
     * @param array $options
     * @return GoogleGeocode
     */
    public function create(array $options = [])
    {
        return new GoogleGeocode($options['google_api_key']);
    }
}
