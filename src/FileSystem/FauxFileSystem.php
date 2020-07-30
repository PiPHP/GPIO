<?php

namespace PiPHP\GPIO\FileSystem;

class FauxFileSystem implements FileSystemInterface {
    private $fileSystem = [];

    public function open( $path, $mode ) {}

    public function getContents($path){
        if( !isset($this->fileSystem[$path]) ){
            $this->fileSystem[$path] = '';
        }

        return $this->fileSystem[$path];
    }

    public function putContents($path, $buffer){
        $this->fileSystem[$path] = $buffer;
    }
}