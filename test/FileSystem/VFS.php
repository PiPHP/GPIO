<?php

namespace PiPHP\Test\GPIO\FileSystem;

use PiPHP\GPIO\FileSystem\FileSystemInterface;

class VFS implements FileSystemInterface
{
    private $vfs = [];

    public function open(string $path, string $mode)
    {
    }

    public function getContents(string $path): string
    {
        return $this->vfs[$path];
    }

    public function putContents(string $path, string $buffer): int
    {
        $this->vfs[$path] = $buffer;
        return 0;
    }

    public function exists(string $path): bool
    {
        $regex = sprintf('#^%s(\/.+)?$#', preg_quote($path, '#'));

        return !empty(array_filter(array_keys($this->vfs), function ($key) use ($regex) {
            return preg_match($regex, $key);
        }));
    }

    public function isDir(string $path): bool
    {
        return $this->exists($path) && !array_key_exists($path, $this->vfs);
    }
}
