<?php

namespace PiPHP\GPIO\FileSystem;

final class FileSystem implements FileSystemInterface
{
    /**
     * {@inheritdoc}
     */
    public function getContents($path)
    {
        $result = @file_get_contents($path);

        if (false === $result) {
            $errorDetails = error_get_last();
            throw new \RuntimeException($errorDetails['message']);
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function putContents($path, $buffer, $flags = 0)
    {
        $result = @file_put_contents($path, $buffer, $flags);

        if (false === $result) {
            $errorDetails = error_get_last();
            throw new \RuntimeException($errorDetails['message']);
        }

        return $result;
    }
}
