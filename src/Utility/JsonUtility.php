<?php

namespace MC\StreamsAPI\Utility;

/**
 * Simple utility for handling JSON data
 */
class JsonUtility
{
    /**
     * Decode a JSON string
     *
     * @param string $json
     * @return mixed|null
     */
    public static function decode(string $json)
    {
        $data = json_decode($json, true, 24);

        // catch decode errors
        if (json_last_error() !== JSON_ERROR_NONE) {
            return null;
        }

        return $data;
    }

    /**
     * Encode a data array as a JSON string
     *
     * @param mixed $data
     * @return string
     */
    public static function encode($data)
    {
        $json = json_encode($data);

        // catch encode errors
        if (json_last_error() !== JSON_ERROR_NONE) {
            return null;
        }

        return $json;
    }
}
