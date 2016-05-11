<?php

namespace PiPHP\GPIO;

use PiPHP\GPIO\FileSystem\FileSystem;
use PiPHP\GPIO\FileSystem\FileSystemInterface;

final class InterruptWatcherFactory implements InterruptWatcherFactoryInterface
{
    private $fileSystem;
    private $streamSelect;

    /**
     * Constructor.
     * 
     * @param FileSystemInterface $fileSystem Optional file system object to use
     * @param callable $streamSelect The stream select implementation to use
     */
    public function __construct(FileSystemInterface $fileSystem = null, $streamSelect = 'stream_select')
    {
        $this->fileSystem = $fileSystem ?: new FileSystem();
        $this->streamSelect = $streamSelect;
    }

    /**
     * {@inheritdoc}
     */
    public function createWatcher()
    {
        return new InterruptWatcher($this->fileSystem, $this->streamSelect);
    }
}
