<?php

namespace Jowy\RabbitInternet\Contracts;

/**
 * Interface Geocode
 * @package Jowy\RabbitInternet\Contracts
 */
interface Geocode
{
    /**
     * @param string $city
     * @return array
     */
    public function searchLocationByCity($city);
}
