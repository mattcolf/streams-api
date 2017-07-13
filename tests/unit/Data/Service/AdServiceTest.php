<?php

namespace MC\StreamsAPI\Data\Service;

use GuzzleHttp\ClientInterface;
use Mockery;
use PHPUnit_Framework_TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class FixtureStreamRepositoryTest extends PHPUnit_Framework_TestCase
{
    public function testGetSuccess()
    {
        $template = 'http://fake_uri.com/%s';

        $http = Mockery::mock(ClientInterface::class);

        $stream = Mockery::mock(StreamInterface::class, [
            'getContents' => json_encode([
                'a1', 'a2'
            ])
        ]);

        $response = Mockery::mock(ResponseInterface::class, [
            'getStatusCode' => 200,
            'getBody' => $stream
        ]);

        $id = 'a6';
        $uri = sprintf($template, $id);

        $http
            ->shouldReceive('request')
            ->with('GET', $uri, ['http_errors' => false])
            ->andReturn($response);

        $service = new AdService($http, $template);

        $ads = $service->get($id);

        $this->assertEquals(['a1', 'a2'], $ads);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetNotFound()
    {
        $template = 'http://fake_uri.com/%s';

        $http = Mockery::mock(ClientInterface::class);

        $response = Mockery::mock(ResponseInterface::class, [
            'getStatusCode' => 400
        ]);

        $id = 'a6';
        $uri = sprintf($template, $id);

        $http
            ->shouldReceive('request')
            ->with('GET', $uri, ['http_errors' => false])
            ->andReturn($response);

        $service = new AdService($http, $template);

        $ads = $service->get($id);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetDecodeError()
    {
        $template = 'http://fake_uri.com/%s';

        $http = Mockery::mock(ClientInterface::class);

        $stream = Mockery::mock(StreamInterface::class, [
            'getContents' => 'invalid json string'
        ]);

        $response = Mockery::mock(ResponseInterface::class, [
            'getStatusCode' => 200,
            'getBody' => $stream
        ]);

        $id = 'a6';
        $uri = sprintf($template, $id);

        $http
            ->shouldReceive('request')
            ->with('GET', $uri, ['http_errors' => false])
            ->andReturn($response);

        $service = new AdService($http, $template);

        $ads = $service->get($id);
    }
}
