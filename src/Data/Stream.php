<?php

namespace MC\StreamsAPI\Data;

use JsonSerializable;

/**
 * A simple Stream model object
 */
class Stream implements JsonSerializable
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $url;

    /**
     * @var array
     */
    private $captions;

    /**
     * @var array
     */
    private $ads;

    /**
     * @param string $id
     * @param string $url
     * @param array $captions
     * @param array $ads
     */
    public function __construct(string $id, string $url, array $captions, array $ads)
    {
        $this->id = $id;
        $this->url = $url;
        $this->captions = $captions;
        $this->ads = $ads;
    }

    /**
     * @return string
     */
    public function id() : string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function url() : string
    {
        return $this->url;
    }

    /**
     * @return array
     */
    public function captions() : array
    {
        return $this->captions;
    }

    /**
     * @return array
     */
    public function ads() : array
    {
        return $this->ads;
    }

    /**
     * Prepare data for JSON serialization
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'streamUrl' => $this->url,
            'captions' => $this->captions,
            'ads' => $this->ads
        ];
    }
}
