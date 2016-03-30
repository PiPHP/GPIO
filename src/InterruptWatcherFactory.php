<?php

namespace PiPHP\GPIO;

final class InterruptWatcherFactory implements InterruptWatcherFactoryInterface
{
    public function createWatcher()
    {
        return new InterruptWatcher();
    }
}
