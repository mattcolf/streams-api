<?php

namespace MC\StreamsAPI\Data;

use GuzzleHttp\ClientInterface;
use MC\StreamsAPI\Data\Service\AdService;
use Mockery;
use PHPUnit_Framework_TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class FixtureStreamRepositoryTest extends PHPUnit_Framework_TestCase
{
    public function testGetAllSuccess()
    {
        $uri = 'http://fake_uri.com';

        $http = Mockery::mock(ClientInterface::class);

        $stream = Mockery::mock(StreamInterface::class, [
            'getContents' => json_encode([
                [
                    '_id' => 'a1',
                    'streamUrl' => 'a2',
                    'captions' => ['a3', 'a4']
                ],
                [
                    '_id' => 'b1',
                    'streamUrl' => 'b2',
                    'captions' => ['b3', 'b4']
                ]
            ])
        ]);

        $response = Mockery::mock(ResponseInterface::class, [
            'getStatusCode' => 200,
            'getBody' => $stream
        ]);

        $http
            ->shouldReceive('request')
            ->with('GET', $uri, ['http_errors' => false])
            ->andReturn($response);

        $ads = Mockery::mock(AdService::class, [
            'get' => ['ads', 'ads', 'ads']
        ]);

        $repository = new FixtureStreamRepository($http, $uri, $ads);

        $streams = $repository->getAll();

        $this->assertTrue(is_array($streams));
        $this->assertEquals(2, count($streams));

        $this->assertEquals('a1', $streams[0]->id());
        $this->assertEquals('a2', $streams[0]->url());
        $this->assertEquals(['a3', 'a4'], $streams[0]->captions());
        $this->assertEquals(['ads', 'ads', 'ads'], $streams[0]->ads());

        $this->assertEquals('b1', $streams[1]->id());
        $this->assertEquals('b2', $streams[1]->url());
        $this->assertEquals(['b3', 'b4'], $streams[1]->captions());
        $this->assertEquals(['ads', 'ads', 'ads'], $streams[1]->ads());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetAllError()
    {
        $uri = 'http://fake_uri.com';

        $http = Mockery::mock(ClientInterface::class);

        $response = Mockery::mock(ResponseInterface::class, [
            'getStatusCode' => 400,
        ]);

        $http
            ->shouldReceive('request')
            ->with('GET', $uri, ['http_errors' => false])
            ->andReturn($response);

        $ads = Mockery::mock(AdService::class);

        $repository = new FixtureStreamRepository($http, $uri, $ads);

        $streams = $repository->getAll();
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetAllDecodeError()
    {
        $uri = 'http://fake_uri.com';

        $http = Mockery::mock(ClientInterface::class);

        $stream = Mockery::mock(StreamInterface::class, [
            'getContents' => 'bad json string'
        ]);

        $response = Mockery::mock(ResponseInterface::class, [
            'getStatusCode' => 200,
            'getBody' => $stream
        ]);

        $http
            ->shouldReceive('request')
            ->with('GET', $uri, ['http_errors' => false])
            ->andReturn($response);

        $ads = Mockery::mock(AdService::class);

        $repository = new FixtureStreamRepository($http, $uri, $ads);

        $streams = $repository->getAll();
    }

    public function testGetFound()
    {
        $uri = 'http://fake_uri.com';

        $http = Mockery::mock(ClientInterface::class);

        $stream = Mockery::mock(StreamInterface::class, [
            'getContents' => json_encode([
                [
                    '_id' => 'a1',
                    'streamUrl' => 'a2',
                    'captions' => ['a3', 'a4']
                ],
                [
                    '_id' => 'b1',
                    'streamUrl' => 'b2',
                    'captions' => ['b3', 'b4']
                ]
            ])
        ]);

        $response = Mockery::mock(ResponseInterface::class, [
            'getStatusCode' => 200,
            'getBody' => $stream
        ]);

        $http
            ->shouldReceive('request')
            ->with('GET', $uri, ['http_errors' => false])
            ->andReturn($response);

        $ads = Mockery::mock(AdService::class, [
            'get' => ['ads', 'ads', 'ads']
        ]);

        $repository = new FixtureStreamRepository($http, $uri, $ads);

        $stream = $repository->get('a1');

        $this->assertTrue($stream instanceof Stream);

        $this->assertEquals('a1', $stream->id());
        $this->assertEquals('a2', $stream->url());
        $this->assertEquals(['a3', 'a4'], $stream->captions());
        $this->assertEquals(['ads', 'ads', 'ads'], $stream->ads());
    }

    public function testGetNotFound()
    {
        $uri = 'http://fake_uri.com';

        $http = Mockery::mock(ClientInterface::class);

        $stream = Mockery::mock(StreamInterface::class, [
            'getContents' => json_encode([
                [
                    '_id' => 'a1',
                    'streamUrl' => 'a2',
                    'captions' => ['a3', 'a4']
                ],
                [
                    '_id' => 'b1',
                    'streamUrl' => 'b2',
                    'captions' => ['b3', 'b4']
                ]
            ])
        ]);

        $response = Mockery::mock(ResponseInterface::class, [
            'getStatusCode' => 200,
            'getBody' => $stream
        ]);

        $http
            ->shouldReceive('request')
            ->with('GET', $uri, ['http_errors' => false])
            ->andReturn($response);

        $ads = Mockery::mock(AdService::class);

        $repository = new FixtureStreamRepository($http, $uri, $ads);

        $stream = $repository->get('c1');

        $this->assertTrue(is_null($stream));
    }
}
