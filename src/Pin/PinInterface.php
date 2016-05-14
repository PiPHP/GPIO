<?php

namespace PiPHP\GPIO\Pin;

interface PinInterface
{
    const VALUE_LOW = 0;
    const VALUE_HIGH = 1;

    /**
     * Get the pin number.
     * 
     * @return int
     */
    public function getNumber();

    /**
     * Export the pin.
     */
    public function export();

    /**
     * Unexport the pin.
     */
    public function unexport();

    /**
     * Get the pin value.
     * 
     * @return int
     */
    public function getValue();
}
