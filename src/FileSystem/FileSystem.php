<?php

namespace PiPHP\GPIO\FileSystem;

final class FileSystem implements FileSystemInterface
{
    /**
     * {@inheritdoc}
     */
    public function open($path, $mode)
    {
        $stream = @fopen($path, $mode);

        if (false === $stream) {
            $errorDetails = error_get_last();
            throw new \RuntimeException($errorDetails['message']);
        }

        return new Stream($stream);
    }
}
