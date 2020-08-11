<?php

namespace PiPHP\GPIO\FileSystem;

interface FileSystemInterface
{
    /**
     * Checks whether a file or directory exists
     *
     * @param string $path Path to the file or directory
     *
     * @return bool true if file exists, false otherwise
     */
    public function exists($path);

    /**
     * Tells whether the filename is a directory
     *
     * @param string $path Path to the file/directory
     *
     * @return bool true if the filename exists and is a directory, false otherwise
     */
    public function isDir($path);

    /**
     * Open a file.
     *
     * @param string $path The path of the file to open
     * @param string $mode The mode to open the file in (see fopen())
     *
     * @return resource A stream resource.
     */
    public function open($path, $mode);

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
     *
     * @return int The number of bytes written
     */
    public function putContents($path, $buffer);
}
