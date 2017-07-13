<?php

namespace MC\StreamsAPI\Controller;

use MC\StreamsAPI\Data\Stream;
use MC\StreamsAPI\Data\StreamRepositoryInterface;
use MC\StreamsAPI\Utility\JsonUtility;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Controller for displaying a single stream
 */
class StreamController implements ControllerInterface
{
    /**
     * @var StreamRepositoryInterface
     */
    private $repository;

    /**
     * @param StreamRepositoryInterface $repository
     */
    public function __construct(StreamRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response
    ) : ResponseInterface {

        $response = $response->withHeader('Content-Type', 'application/json');

        $stream = $this->repository->get($request->getAttribute('stream'));

        if (!$stream instanceof Stream) {
            $response = $response->withStatus(404);
            $response->getBody()->write(JsonUtility::encode([]));

            return $response;
        }

        $response = $response->withStatus(200);
        $response->getBody()->write(JsonUtility::encode($stream));

        return $response;
    }
}
