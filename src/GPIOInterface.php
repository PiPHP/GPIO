<?php

namespace PiPHP\GPIO;

use PiPHP\GPIO\Interrupt\InterruptWatcherInterface;
use PiPHP\GPIO\Pin\InputPinInterface;
use PiPHP\GPIO\Pin\OutputPinInterface;

interface GPIOInterface
{
    /**
     * Get an input pin.
     * 
     * @param int $number The pin number
     * 
     * @return InputPinInterface
     */
    public function getInputPin($number);

    /**
     * Get an output pin.
     *
     * @param int  $number The pin number
     *
     * @param bool $activeLow
     * @param null $defaultHigh
     *
     * @return \PiPHP\GPIO\Pin\OutputPinInterface
     */
    public function getOutputPin($number, $activeLow = false, $defaultHigh = null);

    /**
     * Create an interrupt watcher.
     *
     * @return InterruptWatcherInterface
     */
    public function createWatcher();
}
