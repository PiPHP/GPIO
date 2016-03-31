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

        $this->exceptionIfFalse($result);

        return $stream;
    }

    /**
     * {@inheritdoc}
     */
    public function getContents($path)
    {
        $result = @file_get_contents($path);

        $this->exceptionIfFalse($result);

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function putContents($path, $buffer, $flags = 0)
    {
        $result = @file_put_contents($path, $buffer, $flags);

        $this->exceptionIfFalse($result);

        return $result;
    }

    private function exceptionIfFalse($result)
    {
        if (false === $result) {
            $errorDetails = error_get_last();
            throw new \RuntimeException($errorDetails['message']);
        }
    }
}
