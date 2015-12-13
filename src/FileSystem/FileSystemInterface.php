<?php

namespace PiPHP\GPIO\FileSystem;

interface FileSystemInterface
{
    /**
     * @param string $path
     * @param string $mode
     * 
     * @return StreamInterface
     */
    public function open($path, $mode);
}
