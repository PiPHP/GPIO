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

        $this->exceptionIfFalse($stream);

        return $stream;
    }

    /**
     * {@inheritdoc}
     */
    public function getContents($path)
    {
        $stream = $this->open($path, 'r');

        $this->exceptionIfFalse($stream);

        $result = @stream_get_contents($stream);
        fclose($stream);

        $this->exceptionIfFalse($result);

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function putContents($path, $buffer)
    {
        $stream = $this->open($path, 'w');

        $this->exceptionIfFalse($stream);

        $result = @fwrite($stream, $buffer);
        fclose($stream);

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
