<?php

namespace MC\StreamsAPI;

use Slim\App;
use MC\StreamsAPI\Bootstrap\Container;
use MC\StreamsAPI\Bootstrap\RouteLoader;
use Psr\Container\ContainerInterface;

$root = __DIR__ . '/../';

require_once $root . 'vendor/autoload.php';

/**
 * Get the DI Container
 *
 * @var $container ContainerInterface
 */
$container = Container::load($root);

/**
 * Get the Slim Application
 *
 * @var $app App
 */
$app = $container->get('slim');

/**
 * Load route from configuration into the Slim Application
 *
 * @var $loader RouteLoader
 */
$loader = $container->get('route.loader');
$loader($app);

return $app;
