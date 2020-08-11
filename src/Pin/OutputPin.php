<?php

namespace PiPHP\GPIO\Pin;

use PiPHP\GPIO\FileSystem\FileSystemInterface;

final class OutputPin extends Pin implements OutputPinInterface
{
    /**
     * Constructor.
     *
     * @param FileSystemInterface $fileSystem An object that provides file system access
     * @param int                 $number     The number of the pin
     */
    public function __construct(FileSystemInterface $fileSystem, $number, $exportDirection = self::DIRECTION_OUT)
    {
        parent::__construct($fileSystem, $number);

        $direction = self::DIRECTION_OUT;

        if ($this->exported) {
            $direction = $exportDirection;
        }

        $this->setDirection($direction);
    }

    /**
     * {@inheritdoc}
     */
    public function setValue($value)
    {
        $valueFile = $this->getPinFile(self::GPIO_PIN_FILE_VALUE);
        $this->fileSystem->putContents($valueFile, $value);
    }
}
