<?php

namespace PiPHP\GPIO;

interface InterruptWatcherInterface
{
    /**
     * Add a pin to the watcher.
     *
     * @param PinInterface $pin
     * @param callable     $callback
     */
    public function addPin(PinInterface $pin, callable $callback);

    /**
     * Remove a pin from the watcher.
     *
     * @param PinInterface $pin
     */
    public function removePin(PinInterface $pin);

    /**
     * Watch for pin interrupts.
     *
     * @param int $timeout The maximum time to watch for in milliseconds.
     */
    public function watch($timeout);
}
