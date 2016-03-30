<?php

namespace PiPHP\GPIO;

use PiPHP\GPIO\FileSystem\FileSystemInterface;

class InterruptWatcher implements InterruptWatcherInterface
{
    private $fileSystem;
    private $streams;
    private $callbacks;

    /**
     * Constructor.
     * 
     * @param FileSystemInterface $fileSystem An object that provides file system access
     */
    public function __construct(FileSystemInterface $fileSystem)
    {
        $this->fileSystem = $fileSystem;
        $this->streams = $this->callbacks = [];
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
            $this->streams[$pinNumber]->close();
            unset($this->streams[$pinNumber]);
            unset($this->callbacks[$pinNumber]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function watch($timeout)
    {
        
    }
}
