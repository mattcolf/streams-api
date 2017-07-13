<?php

namespace MC\StreamsAPI\Middleware;

use Exception;
use Firebase\JWT\JWT;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * A basic JWT based authentication middleware
 */
class AuthenticationMiddleware implements MiddlewareInterface
{
    const HEADER = 'Authorization';
    const ERR_ACCESS_DENIED = 'Access denied. You must provide a valid Authentication header.';

    /**
     * @var string
     */
    private $secret;

    /**
     * @var bool
     */
    private $debug;

    /**
     * @param string $secret
     * @param bool $debug
     */
    public function __construct(string $secret, bool $debug)
    {
        $this->secret = $secret;
        $this->debug = $debug;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param callable $next
     * @return ResponseInterface
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next
    ) : ResponseInterface {

        // don't require authentication in debug mode
        if ($this->debug) {
            return $next($request, $response);
        }

        if (!$request->hasHeader(static::HEADER)) {
            return $this->getAccessDenied($response);
        }

        $header = $request->getHeader(static::HEADER);
        $header = reset($header);

        try {
            $content = JWT::decode($header, $this->secret, ['HS256']);
        } catch (Exception $e) {
            // normally, do some more error handling here
            return $this->getAccessDenied($response);
        }

        // normally, so something with the content of the jwt
        // log request, rate limit per user, etc.
        // $content

        // call the next item in the stack
        return $next($request, $response);
    }

    private function getAccessDenied(ResponseInterface $response) : ResponseInterface
    {
        $response = $response->withStatus(403);
        $response->getBody()->write(json_encode(['message' => static::ERR_ACCESS_DENIED]));

        return $response;
    }
}
