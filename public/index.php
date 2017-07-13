<?php

use Slim\App;

/**
 * Load and run the Slim Application
 *
 * @var $app App
 */
$app = require_once __DIR__.'/../src/bootstrap.php';
$app->run();
