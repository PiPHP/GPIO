<?php

namespace PiPHP\GPIO\FileSystem;

interface StreamInterface
{
    /**
     * Read data from the stream.
     * 
     * @param int $length The length to read
     */
    public function read($length);

    /**
     * Write to the stream.
     * 
     * @param string $buffer The buffer to write
     */
    public function write($buffer);

    /**
     * Close the stream.
     */
    public function close();
}
