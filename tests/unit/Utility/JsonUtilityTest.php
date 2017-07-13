<?php

namespace MC\StreamsAPI\Utility;

use Mockery;
use PHPUnit_Framework_TestCase;

class JsonUtilityTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider encodeData
     */
    public function testEncode($data, $expected)
    {
        $this->assertEquals($expected, JsonUtility::encode($data));
    }

    public function encodeData()
    {
        return [
            // success
            [
                'foo',
                '"foo"'
            ],
            [
                ['foo' => 'bar'],
                '{"foo":"bar"}'
            ],
            // failure
            [
                fopen('php://temp', 'r'),
                null
            ]
        ];
    }

    /**
     * @dataProvider decodeData
     */
    public function testDecode($json, $expected)
    {
        $this->assertEquals($expected, JsonUtility::decode($json));
    }

    public function decodeData()
    {
        return [
            // success
            [
                '"foo"',
                'foo'
            ],
            [
                '{"foo":"bar"}',
                ['foo' => 'bar']
            ],
            // failure
            [
                'this is not a valid json string',
                null
            ]
        ];
    }
}
