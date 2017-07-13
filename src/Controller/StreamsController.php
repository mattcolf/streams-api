<?php

namespace MC\StreamsAPI\Controller;

use MC\StreamsAPI\Data\StreamRepositoryInterface;
use MC\StreamsAPI\Utility\JsonUtility;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Controller for displaying all streams
 */
class StreamsController implements ControllerInterface
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

        $streams = $this->repository->getAll();

        $response = $response->withStatus(200);
        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(JsonUtility::encode($streams));

        return $response;
    }
}
