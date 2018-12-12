<?php

namespace PiPHP\GPIO\FileSystem;

use RuntimeException;

final class FileSystem implements FileSystemInterface
{
    /**
     * {@inheritdoc}
     */
    public function open($path, $mode)
    {
        $stream = @fopen($path, $mode);

        $this->exceptionIfFalse($stream);

        return $stream;
    }

    /**
     * {@inheritdoc}
     */
    public function getContents($path)
    {
        $stream = $this->open($path, 'r');

        $contents = @stream_get_contents($stream);
        fclose($stream);

        $this->exceptionIfFalse($contents);

        return $contents;
    }

    /**
     * {@inheritdoc}
     */
    public function putContents($path, $buffer)
    {
        $stream = $this->open($path, 'w');

        $bytesWritten = @fwrite($stream, $buffer);
        fclose($stream);

        $this->exceptionIfFalse($bytesWritten);

        return $bytesWritten;
    }

    private function exceptionIfFalse($result)
    {
        if (false === $result) {
            $errorDetails = error_get_last();
            throw new RuntimeException($errorDetails['message']);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function exists($path)
    {
        return file_exists($path);
    }

    /**
     * {@inheritdoc}
     */
    public function isDir($path)
    {
        return is_dir($path);
    }
}
