<?php

namespace PiPHP\GPIO;

final class InterruptWatcherFactory implements InterruptWatcherFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function createWatcher()
    {
        return new InterruptWatcher();
    }
}
