<?php

namespace PiPHP\GPIO;

interface InterruptWatcherInterface
{
    /**
     * Register a callback to fire on pin interrupts. Only one callback can be registered per pin, this method will overwrite.
     *
     * @param PinInterface $pin
     * @param callable     $callback
     */
    public function register(PinInterface $pin, callable $callback);

    /**
     * Unregister a pin callback.
     *
     * @param PinInterface $pin
     */
    public function unregister(PinInterface $pin);

    /**
     * Watch for pin interrupts.
     *
     * @param int $timeout The maximum time to watch for in milliseconds.
     */
    public function watch($timeout);
}
