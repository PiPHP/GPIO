<?php

namespace PiPHP\GPIO\FileSystem;

use PiPHP\GPIO\Pin\Pin;

class FileSystemFactory {
    public static function getFileSystem(): FileSystemInterface {
        if( is_dir(Pin::GPIO_PATH) ){
            return new FileSystem;
        } else {
            return new FauxFileSystem;
        }
    }
}