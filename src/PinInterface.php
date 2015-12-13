<?php

namespace PiPHP\GPIO;

interface PinInterface
{
    const DIRECTION_IN  = 'in';
    const DIRECTION_OUT = 'out';

    const VALUE_LOW  = 0;
    const VALUE_HIGH = 1;

    const EDGE_NONE    = 'none';
    const EDGE_BOTH    = 'both';
    const EDGE_RISING  = 'rising';
    const EDGE_FALLING = 'falling';

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
     * Get the pin direction.
     * 
     * @return string
     */
    public function getDirection();

    /**
     * Set the pin direction.
     * 
     * @param string $direction The direction to set
     */
    public function setDirection($direction);

    /**
     * Get the pin value.
     * 
     * @return int
     */
    public function getValue();

    /**
     * Set the pin value.
     * 
     * @param int $value The value to set
     */
    public function setValue($value);

    /**
     * Get the pin edge.
     * 
     * @return string The pin edge value
     */
    public function getEdge();

    /**
     * Set the pin edge.
     * 
     * @param string $edge The pin edge value to set
     */
    public function setEdge($edge);
}
