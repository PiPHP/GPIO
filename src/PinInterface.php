<?php

namespace PiPHP\GPIO;

interface PinInterface
{
    const DIRECTION_IN  = 'in';
    const DIRECTION_OUT = 'out';

    const VALUE_LOW  = '0';
    const VALUE_HIGH = '1';

    const EDGE_NONE    = 'none';
    const EDGE_BOTH    = 'both';
    const EDGE_RISING  = 'rising';
    const EDGE_FALLING = 'falling';

    public function getNumber();

    public function export();

    public function unexport();

    public function getDirection();

    public function setDirection($direction);

    public function getValue();

    public function setValue($value);

    public function getEdge();

    public function setEdge($edge);
}
