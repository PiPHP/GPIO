<?php

namespace PiPHP\Test\GPIO;

use PHPUnit\Framework\TestCase;
use PiPHP\GPIO\GPIO;
use PiPHP\GPIO\FileSystem\FileSystemInterface;

class InterruptWatcherTest extends TestCase
{
    public function testWatcher()
    {
        // Create stream pairs initialised with a value to read
        $streamPair2 = stream_socket_pair(STREAM_PF_UNIX, STREAM_SOCK_STREAM, STREAM_IPPROTO_IP);
        $streamPair3 = stream_socket_pair(STREAM_PF_UNIX, STREAM_SOCK_STREAM, STREAM_IPPROTO_IP);

        fwrite($streamPair2[1], '0');
        fwrite($streamPair3[1], '0');

        // Mock the file system with the stream pairs
        $fileSystem = $this
            ->getMockBuilder(FileSystemInterface::class)
            ->setMethods(['open', 'getContents', 'putContents', 'exists', 'isDir'])
            ->getMock();

        $map = [
            ['/sys/class/gpio/gpio2/value', 'r', $streamPair2[0]],
            ['/sys/class/gpio/gpio3/value', 'r', $streamPair3[0]],
        ];

        $fileSystem->method('open')
            ->will($this->returnValueMap($map));

        // Maps $except to $read for testing
        $streamSelect = function ($read, $write, $except) {
            $dummy = [];
            return stream_select($except, $write, $dummy, 0);
        };

        // Create the GPIO object using our mock dependencies
        $gpio = new GPIO($fileSystem, $streamSelect);
        $watcher = $gpio->createWatcher();

        // Log variables for the number of interrupts triggered
        $callCount2 = $callCount3 = 0;

        // Register handlers
        $pin2 = $gpio->getInputPin(2);

        $watcher->register($pin2, function ($pin, $value) use (&$callCount2, $pin2) {
            $callCount2++;
            $this->assertSame($pin2, $pin);
            $this->assertEquals(1, $value);

            return true;
        });

        $pin3 = $gpio->getInputPin(3);

        $watcher->register($pin3, function ($pin, $value) use (&$callCount3, $pin3) {
            $callCount3++;
            $this->assertSame($pin3, $pin);
            $this->assertEquals(0, $value);

            return true;
        });

        // Trigger interrupts
        fwrite($streamPair2[1], '1');
        $watcher->watch(100);

        fwrite($streamPair2[1], '1');
        fwrite($streamPair3[1], '0');
        $watcher->watch(100);

        // Unregister one of the handlers
        $watcher->unregister($pin2);

        @fwrite($streamPair2[1], '0');
        $watcher->watch(100);

        // Check expected number of interrupts
        $this->assertEquals(2, $callCount2);
        $this->assertEquals(3, $callCount3);

        fclose($streamPair2[1]);
        fclose($streamPair3[1]);
    }
}
