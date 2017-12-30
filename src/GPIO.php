<?php

namespace PiPHP\GPIO;

use PiPHP\GPIO\FileSystem\FileSystem;
use PiPHP\GPIO\FileSystem\FileSystemInterface;
use PiPHP\GPIO\Interrupt\InterruptWatcher;
use PiPHP\GPIO\Pin\Pin;
use PiPHP\GPIO\Pin\InputPin;
use PiPHP\GPIO\Pin\OutputPin;

final class GPIO implements GPIOInterface
{
    private $fileSystem;
    private $streamSelect;

    /**
     * Constructor.
     *
     * @param FileSystemInterface|null $fileSystem Optional file system object to use
     * @param callable|null $streamSelect Optional stream select callable
     */
    public function __construct(FileSystemInterface $fileSystem = null, callable $streamSelect = null)
    {
        $this->fileSystem = $fileSystem ?: new FileSystem();
        $this->streamSelect = $streamSelect ?: 'stream_select';
    }

    /**
     * {@inheritdoc}
     */
    public function getInputPin($number)
    {
        return new InputPin($this->fileSystem, $number);
    }

    /**
     * {@inheritdoc}
     */
    public function getOutputPin($number, $exportDirection = Pin::DIRECTION_OUT)
    {
        if ($exportDirection !== Pin::DIRECTION_OUT && $exportDirection !== Pin::DIRECTION_LOW && $exportDirection !== Pin::DIRECTION_HIGH) {
            throw new \InvalidArgumentException('exportDirection has to be an OUT type (OUT/LOW/HIGH).');
        }

        return new OutputPin($this->fileSystem, $number, $exportDirection);
    }

    /**
     * {@inheritdoc}
     */
    public function createWatcher()
    {
        return new InterruptWatcher($this->fileSystem, $this->streamSelect);
    }
}
