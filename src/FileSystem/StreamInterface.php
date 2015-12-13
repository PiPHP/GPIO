<?php

namespace PiPHP\GPIO\FileSystem;

interface StreamInterface
{
    public function read($length);

    public function write($buffer);

    public function close();
}
