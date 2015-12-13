<?php

namespace PiPHP\GPIO\FileSystem;

interface FileSystemInterface
{
    /**
     * Open a file for reading or writing.
     * 
     * @param string $path The path of the file to open
     * @param string $mode The mode to open the file in (see fopen())
     * 
     * @return StreamInterface
     */
    public function open($path, $mode);
}
