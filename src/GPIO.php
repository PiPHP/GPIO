<?php

namespace PiPHP\GPIO;

use PiPHP\GPIO\FileSystem\FileSystem;
use PiPHP\GPIO\FileSystem\FileSystemInterface;
use PiPHP\GPIO\Interrupt\InterruptWatcher;
use PiPHP\GPIO\Pin\InputPin;
use PiPHP\GPIO\Pin\OutputPin;

final class GPIO implements GPIOInterface
{
    private $fileSystem;
    private $streamSelect;

    /**
     * Constructor.
     * 
     * @param FileSystemInterface $fileSystem Optional file system object to use
     * @param callable $streamSelect Optional sream select callable
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
    public function getOutputPin($number)
    {
        return new OutputPin($this->fileSystem, $number);
    }

    /**
     * {@inheritdoc}
     */
    public function createWatcher()
    {
        return new InterruptWatcher($this->fileSystem, $this->streamSelect);
    }
}
