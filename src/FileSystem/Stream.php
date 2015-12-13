<?php

namespace PiPHP\GPIO\FileSystem;

final class Stream implements StreamInterface
{
    private $stream;

    /**
     * Constructor.
     * 
     * @param resource $stream The resource to wrap
     */
    public function __construct($stream)
    {
        $this->stream = $stream;
    }

    /**
     * {@inheritdoc}
     */
    public function read($length)
    {
        return fread($this->stream, $length);
    }

    /**
     * {@inheritdoc}
     */
    public function write($buffer)
    {
        return fwrite($this->stream, $buffer);
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        fclose($this->stream);
    }
}
