<?php

namespace PiPHP\GPIO;

interface InterruptWatcherFactoryInterface
{
    /**
     * Create an interrupt watcher.
     *
     * @return InterruptWatcherInterface
     */
    public function createWatcher();
}
