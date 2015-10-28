<?php

namespace Jowy\RabbitInternet\Contracts;

/**
 * Interface Factory
 * @package Jowy\RabbitInternet\Contracts
 */
interface Factory
{
    /**
     * @param array $options
     * @return object
     */
    public function create(array $options = []);
}
