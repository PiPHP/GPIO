<?php

namespace PiPHP\GPIO\Interrupt;

use PiPHP\GPIO\Pin\InputPinInterface;

interface InterruptWatcherInterface
{
    /**
     * Register a callback to fire on pin interrupts. Only one callback can be registered per pin, this method will overwrite.
     *
     * @param InputPinInterface $pin
     * @param callable $callback
     * @param integer $delay time in ms between callbacks
     */
    public function register(InputPinInterface $pin, callable $callback, int $delay = 0);

    /**
     * Unregister a pin callback.
     *
     * @param InputPinInterface $pin
     */
    public function unregister(InputPinInterface $pin);

    /**
     * Watch for pin interrupts.
     *
     * @param int $timeout The maximum time to watch for in milliseconds.
     */
    public function watch($timeout);
}
