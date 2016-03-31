<?php

namespace PiPHP\GPIO;

use PiPHP\GPIO\FileSystem\FileSystemInterface;

class InterruptWatcher implements InterruptWatcherInterface
{
    private $fileSystem;
    private $streams;
    private $callbacks;
    private $triggers;

    /**
     * Constructor.
     * 
     * @param FileSystemInterface $fileSystem An object that provides file system access
     */
    public function __construct(FileSystemInterface $fileSystem)
    {
        $this->fileSystem = $fileSystem;
        $this->streams = $this->callbacks = $this->triggers = [];
    }

    /**
     * {@inheritdoc}
     */
    public function register(PinInterface $pin, callable $callback)
    {
        $pinNumber = $pin->getNumber();

        if (!isset($this->streams[$pinNumber])) {
            $file = '/sys/class/gpio/gpio' . $pinNumber . '/value';
            $this->streams[$pinNumber] = $this->fileSystem->open($file, 'r');
        }

        $this->callbacks[$pinNumber] = $callback;
    }

    /**
     * {@inheritdoc}
     */
    public function unregister(PinInterface $pin)
    {
        $pinNumber = $pin->getNumber();

        if (isset($this->streams[$pinNumber])) {
            fclose($this->streams[$pinNumber]);

            unset($this->streams[$pinNumber]);
            unset($this->callbacks[$pinNumber]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function watch($timeout)
    {
        $seconds = floor($timeout / 1000);
        $carry = $timeout - ($seconds * 1000);
        $micro = $carry * 1000;

        $read = $write = [];
        $except = $this->streams;

        $result = @stream_select($read, $write, $except, $seconds, $micro);

        if (false === $result) {
            return false;
        }

        foreach ($except as $pinNumber => $stream) {
            $value = fread($stream, 1024);
            rewind($stream);

            $this->triggers[$pinNumber] = $value;
        }

        foreach ($this->triggers as $pinNumber => $value) {
            if (false === call_user_func($this->callbacks[$pinNumber], $value)) {
                return false;
            }
        }

        return true;
    }
}
