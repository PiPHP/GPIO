<?php

namespace PiPHP\GPIO\FileSystem;

final class FileSystem implements FileSystemInterface
{
    /**
     * {@inheritdoc}
     */
    public function open($path, $mode)
    {
        $stream = fopen($path, $mode);
        return new Stream($stream);
    }
}
