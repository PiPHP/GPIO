<?php

namespace PiPHP\GPIO\Interrupt;

use PiPHP\GPIO\FileSystem\FileSystemInterface;
use PiPHP\GPIO\Pin\InputPinInterface;

class InterruptWatcher implements InterruptWatcherInterface
{
    private $fileSystem;
    private $streamSelect;
    private $streams;
    private $pins;
    private $callbacks;

    /**
     * Constructor.
     *
     * @param FileSystemInterface $fileSystem An object that provides file system access
     * @param callable $streamSelect The stream select implementation
     */
    public function __construct(FileSystemInterface $fileSystem, callable $streamSelect)
    {
        $this->fileSystem = $fileSystem;
        $this->streamSelect = $streamSelect;

        $this->streams = $this->pins = $this->callbacks = [];
    }

    /**
     * Destructor.
     */
    public function __destruct()
    {
        foreach ($this->streams as $stream) {
            fclose($stream);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function register(InputPinInterface $pin, callable $callback)
    {
        $pinNumber = $pin->getNumber();

        if (!isset($this->streams[$pinNumber])) {
            $file = '/sys/class/gpio/gpio' . $pinNumber . '/value';
            $this->streams[$pinNumber] = $this->fileSystem->open($file, 'r');
            stream_set_blocking($this->streams[$pinNumber], false);
            fread($this->streams[$pinNumber], 1);
            @rewind($this->streams[$pinNumber]);
        }

        $this->pins[$pinNumber] = $pin;
        $this->callbacks[$pinNumber] = $callback;
    }

    /**
     * {@inheritdoc}
     */
    public function unregister(InputPinInterface $pin)
    {
        $pinNumber = $pin->getNumber();

        if (isset($this->streams[$pinNumber])) {
            fclose($this->streams[$pinNumber]);

            unset($this->streams[$pinNumber]);
            unset($this->callbacks[$pinNumber]);
            unset($this->pins[$pinNumber]);
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

        $streamSelect = $this->streamSelect;
        $result = @$streamSelect($read, $write, $except, $seconds, $micro);

        if (false === $result) {
            return false;
        }

        $triggers = [];

        foreach ($except as $pinNumber => $stream) {
            $value = fread($stream, 1);
            @rewind($stream);

            if ($value !== false) {
                $triggers[$pinNumber] = (int) $value;
            }
        }

        foreach ($triggers as $pinNumber => $value) {
            if (false === call_user_func($this->callbacks[$pinNumber], $this->pins[$pinNumber], $value)) {
                return false;
            }
        }

        return true;
    }
}
