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
     * @param bool|int            $activeLow
     * @param bool                $defaultHigh
     */
    public function __construct(FileSystemInterface $fileSystem, $number, $activeLow = Pin::ACTIVE_LOW_FALSE, $defaultHigh = null)
    {
        parent::__construct($fileSystem, $number);

        if ($activeLow && !isset($defaultHigh)) {
            $defaultHigh = true;
        }
        if ($defaultHigh) {
            $this->setDirection(self::DIRECTION_OUT_HIGH);
        } else {
            $this->setDirection(self::DIRECTION_OUT);
        }

        if ($activeLow) {
            $this->setActiveLow(self::ACTIVE_LOW_TRUE);
        }
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
