<?php

namespace PiPHP\GPIO\Pin;

interface OutputPinInterface extends PinInterface
{
    /**
     * Set the pin value.
     *
     * @param int $value The value to set
     */
    public function setValue($value);
}
