<?php

namespace PiPHP\Test\GPIO;

use PiPHP\GPIO\InterruptWatcherFactory;
use PiPHP\GPIO\FileSystem\FileSystemInterface;
use PiPHP\GPIO\PinFactory;

class InterruptWatcherTest extends \PHPUnit_Framework_TestCase
{
    public function testWatcher()
    {
        $streamPair = stream_socket_pair(STREAM_PF_UNIX, STREAM_SOCK_STREAM, STREAM_IPPROTO_IP);

        $fileSystem = $this
            ->getMockBuilder(FileSystemInterface::class)
            ->setMethods(['open', 'getContents', 'putContents'])
            ->getMock();

        $fileSystem->expects($this->once())
            ->method('open')
            ->with('/sys/class/gpio/gpio2/value', 'r')
            ->willReturn($streamPair[0]);

        // Maps $except to $read for testing
        $streamSelect = function ($read, $write, $except) {
            $dummy = [];
            return stream_select($except, $write, $dummy);
        };

        $watcherFactory = new InterruptWatcherFactory($fileSystem, $streamSelect);
        $watcher = $watcherFactory->createWatcher();

        $callCount = 0;
        $expectedPin = (new PinFactory)->getPin(2);

        $watcher->register($expectedPin, function ($pin, $value) use (&$callCount, $expectedPin) {
            $callCount++;
            $this->assertSame($expectedPin, $pin);
            $this->assertEquals(1, $value);
        });

        fwrite($streamPair[1], '1');
        $watcher->watch(100);
        fwrite($streamPair[1], '1');
        $watcher->watch(100);

        $watcher->unregister($expectedPin);

        @fwrite($streamPair[1], '0');
        $watcher->watch(100);

        $this->assertEquals(2, $callCount);

        fclose($streamPair[1]);
    }
}
