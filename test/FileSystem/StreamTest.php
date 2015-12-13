<?php

namespace PiPHP\Test\GPIO\FileSystem;

use PiPHP\GPIO\FileSystem\Stream;

class StreamTest extends \PHPUnit_Framework_TestCase
{
    public function testStream()
    {
        $streams = stream_socket_pair(STREAM_PF_UNIX, STREAM_SOCK_STREAM, STREAM_IPPROTO_IP);

        $streamA = new Stream($streams[0]);
        $streamB = new Stream($streams[1]);

        $streamA->write('hello');
        $this->assertEquals('hello', $streamB->read(5));

        $streamA->close();
        $streamB->close();
    }
}
