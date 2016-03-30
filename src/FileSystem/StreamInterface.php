<?php

namespace PiPHP\GPIO\FileSystem;

interface StreamInterface
{
    /**
     * Performs the equivalent of a select() system call on the streams provided.
     * 
     * @param self[] &$read
     * @param self[] &$write
     * @param self[] &$except
     * @param int     $tvSec
     * @param int     $tvUSec
     *
     * @return bool
     */
    public static function select(&$read, &$write, &$except, $tvSec, $tvUSec = 0);

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
