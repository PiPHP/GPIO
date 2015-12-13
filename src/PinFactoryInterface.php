<?php

namespace PiPHP\GPIO;

interface PinFactoryInterface
{
    /**
     * Get a pin.
     * 
     * @param int $number The pin number
     * 
     * @return PinInterface
     */
    public function getPin($number);
}
