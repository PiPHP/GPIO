<?php

namespace PiPHP\GPIO;

use PiPHP\GPIO\FileSystem\FileSystem;
use PiPHP\GPIO\FileSystem\FileSystemInterface;

final class PinFactory implements PinFactoryInterface
{
    private $fileSystem;

    public function __construct(FileSystemInterface $fileSystem = null)
    {
        $this->fileSystem = $fileSystem ?: new FileSystem();
    }

    public function getPin($number)
    {
        return new Pin($this->fileSystem, $number);
    }
}
