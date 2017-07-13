<?php

namespace MC\StreamsAPI\Data;

use GuzzleHttp\ClientInterface;
use InvalidArgumentException;
use MC\StreamsAPI\Data\Service\AdService;
use MC\StreamsAPI\Utility\JsonUtility;

/**
 * A stream repository that loads fixture data from a file.
 *
 * Not for use in production systems!
 */
class FixtureStreamRepository implements StreamRepositoryInterface
{
    const ERR_LOAD = 'Unable to load fixture file \'%s\'. Make sure the URI is correct.';
    const ERR_PARSE = 'Unable to parse fixture file \'%s\'. Make sure it is a valid JSON file.';

    /**
     * @var array
     */
    private $data;

    /**
     * @var AdService
     */
    private $ads;

    /**
     * @param ClientInterface $http
     * @param string $uri
     * @param AdService $ads
     */
    public function __construct(ClientInterface $http, string $uri, AdService $ads)
    {
        $this->data = [];
        $this->ads = $ads;

        // map by id for faster lookup
        foreach ($this->load($http, $uri) as $data) {
            $this->data[$data['_id']] = $data;
        }
    }

    /**
     * Get all streams
     *
     * In a real application, it would not be a good idea to load all entities from storage as
     * there could be many. Instead, result sets should be limited and paginated to a size that
     * meets both the business and performance needs.
     *
     * @return Stream[]
     */
    public function getAll() : array
    {
        return array_values(array_map(function (array $data) {
            return $this->factory($data);
        }, $this->data));
    }

    /**
     * Get a single stream by ID
     *
     * @param string $id
     * @return Stream|null
     */
    public function get(string $id)
    {
        return isset($this->data[$id]) ? $this->factory($this->data[$id]) : null;
    }

    /**
     * Load fixture data from a URI
     *
     * @param ClientInterface $http
     * @param string $uri
     * @return array
     * @throws InvalidArgumentException
     */
    private function load(ClientInterface $http, string $uri) : array
    {
        $response = $http->request('GET', $uri, [
            'http_errors' => false
        ]);

        if ($response->getStatusCode() != 200) {
            throw new InvalidArgumentException(sprintf(self::ERR_LOAD, $uri));
        }

        $data = JsonUtility::decode($response->getBody()->getContents());

        if (!is_array($data)) {
            throw new InvalidArgumentException(sprintf(self::ERR_PARSE, $uri));
        }

        return $data;
    }

    /**
     * Construct a Stream object from fixture data
     *
     * @param array $data
     * @return Stream
     */
    private function factory(array $data) : Stream
    {
        $ads = $this->ads->get($data['_id']);

        return new Stream(
            $data['_id'] ?? '',
            $data['streamUrl'] ?? '',
            $data['captions'] ?? [],
            $ads
        );
    }
}
