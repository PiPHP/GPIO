<?php

namespace PiPHP\GPIO\FileSystem;

interface FileSystemInterface
{
    /**
     * Read the contents of a file.
     * 
     * @param string $path The path of the file to read
     * 
     * @return string The file contents
     */
    public function getContents($path);

    /**
     * Write a buffer to a file.
     * 
     * @param string $path   The path of the file to write to
     * @param string $buffer The buffer to write
     * @param int    $flags  Optional flags, as per the documentation of file_put_contents()
     * 
     * @return int The number of bytes written
     */
    public function putContents($path, $buffer, $flags = 0);
}
