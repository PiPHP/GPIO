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

    /**
     * Sets active_low
     * If set to true or 1, it means setValue(HIGH) will result the pin being LOW; also for input, if
     * getValue()==HIGH it means the pin is in reality LOW, no voltage)
     *
     * @param bool|int $activeLow
     */
    public function setActiveLow($activeLow);
}
