<?php

namespace MC\StreamsAPI\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Controller for the root URI
 *
 * Generally only used as a health check endpoint.
 */
class RootController implements ControllerInterface
{
    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response
    ) : ResponseInterface {

        // In a real application, you may wish to run a sanity check on the state of the application.
        // We'll just return a static response for simplicity.
        $response = $response->withStatus(200);
        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode(['status' => 'good']));

        return $response;
    }
}
