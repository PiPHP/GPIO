<?php

namespace PiPHP\GPIO;

class InterruptWatcher implements InterruptWatcherInterface
{
    /**
     * @var resource[]
     */
    private $streams;

    /**
     * @var callable[]
     */
    private $callbacks = array();

    /**
     * {@inheritdoc}
     */
    public function register(PinInterface $pin, callable $callback)
    {
        $this->callbacks[$pin->getNumber()] = $callback;
    }

    /**
     * {@inheritdoc}
     */
    public function unregister(PinInterface $pin)
    {
        unset($this->callbacks[$pin->getNumber()]);
    }

    /**
     * {@inheritdoc}
     */
    public function watch($timeout)
    {
    }
}
