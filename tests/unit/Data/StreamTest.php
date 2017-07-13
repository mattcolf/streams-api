<?php

namespace MC\StreamsAPI\Data;

use Mockery;
use PHPUnit_Framework_TestCase;

class StreamTest extends PHPUnit_Framework_TestCase
{
    public function testPassthough()
    {
        $id = 'a';
        $url = 'b';
        $captions = ['c', 'd'];
        $ads = ['e', 'f'];

        $stream = new Stream($id, $url, $captions, $ads);

        $this->assertEquals($id, $stream->id());
        $this->assertEquals($url, $stream->url());
        $this->assertEquals($captions, $stream->captions());
        $this->assertEquals($ads, $stream->ads());
    }

    public function testSerialize()
    {
        $id = 'a';
        $url = 'b';
        $captions = ['c', 'd'];
        $ads = ['e', 'f'];

        $stream = new Stream($id, $url, $captions, $ads);

        $this->assertEquals([
            'id' => $id,
            'streamUrl' => $url,
            'captions' => $captions,
            'ads' => $ads
        ], $stream->jsonSerialize());
    }
}
