<?php

namespace MC\StreamsAPI\Bootstrap;

use Slim\App;
use Slim\Interfaces\RouteInterface;

/**
 * Load routes from configuration
 *
 * Routes will be lazy loaded from the container at the time of execution.
 */
class RouteLoader
{
    /**
     * @var array
     */
    private $routes;

    /**
     * @param
     * @param array $routes
     */
    public function __construct(array $routes = [])
    {
        $this->routes = $routes;
    }

    /**
     * Load all routes into the application
     *
     * @param App $app
     * @return void
     */
    public function __invoke(App $app)
    {
        foreach ($this->routes as $name => $details) {
            $this->loadRoute($app, $name, $details);
        }
    }

    /**
     * Load a single route into the application
     *
     * @param App $app
     * @param string $name
     * @param array $details
     * @return RouteInterface
     */
    private function loadRoute(App $app, string $name, array $details) : RouteInterface
    {
        $methods = $details['method'] ?? ['GET'];
        $methods = is_array($methods) ? $methods : [$methods];
        $route = $details['route'] ?? '';
        $stack = $details['stack'] ?? [];

        // last item in the stack is considered the controller
        $controller = array_pop($stack);

        $route = $app->map($methods, $route, $controller);
        $route->setName($name);

        // add other middleware to the route
        while ($middleware = array_pop($stack)) {
            $route->add($middleware);
        }

        return $route;
    }
}
