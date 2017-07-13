<?php

namespace MC\StreamsAPI\Data\Service;

use InvalidArgumentException;
use GuzzleHttp\ClientInterface;
use MC\StreamsAPI\Utility\JsonUtility;

/**
 * Service for obtaining ads for a given Stream ID
 */
class AdService
{
    const ERR_LOAD = 'Unable to load ad data from \'%s\'. Make sure the URI is correct.';
    const ERR_PARSE = 'Unable to parse ad data from \'%s\'. Make sure it is a valid JSON file.';

    /**
     * @var ClientInterface
     */
    private $http;

    /**
     * @var string
     */
    private $uri;

    /**
     * @param ClientInterface $http
     * @param string $uri
     */
    public function __construct(ClientInterface $http, string $uri)
    {
        $this->http = $http;
        $this->uri = $uri;
    }

    /**
     * Get Ad data for a given Stream ID
     *
     * @param string $id
     * @return array
     */
    public function get(string $id) : array
    {
        $uri = sprintf($this->uri, $id);

        $response = $this->http->request('GET', $uri, [
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
}
