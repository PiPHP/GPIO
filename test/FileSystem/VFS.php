<?php

namespace PiPHP\Test\GPIO\FileSystem;

use PiPHP\GPIO\FileSystem\FileSystemInterface;

class VFS implements FileSystemInterface
{
    private $vfs = [];

    public function open($path, $mode)
    {
    }

    public function getContents($path)
    {
        return $this->vfs[$path];
    }

    public function putContents($path, $buffer)
    {
        $this->vfs[$path] = $buffer;
    }

    public function exists($path)
    {
        $regex = sprintf('#^%s(\/.+)?$#', preg_quote($path, '#'));

        return !empty(array_filter(array_keys($this->vfs), function ($key) use ($regex) {
            return preg_match($regex, $key);
        }));
    }

    public function isDir($path)
    {
        return $this->exists($path) && !array_key_exists($path, $this->vfs);
    }
}
