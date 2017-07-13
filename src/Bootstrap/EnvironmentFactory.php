<?php

namespace MC\StreamsAPI\Bootstrap;

use Slim\Http\Environment;

/**
 * A simple factory for setting up the Slim Environment
 */
class EnvironmentFactory
{
    /**
     * @return Environment
     */
    public static function load() : Environment
    {
        return new Environment($_SERVER);
    }
}
