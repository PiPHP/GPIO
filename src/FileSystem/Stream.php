<?php

namespace PiPHP\GPIO\FileSystem;

final class Stream implements StreamInterface
{
    private $stream;

    public function __construct($stream)
    {
        $this->stream = $stream;
    }

    public function read($length)
    {
        return fread($this->stream, $length);
    }

    public function write($buffer)
    {
        return fwrite($this->stream, $buffer);
    }

    public function close()
    {
        fclose($this->stream);
    }
}
