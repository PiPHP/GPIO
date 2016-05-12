# PiPHP: GPIO

[![Latest Stable Version](https://poser.pugx.org/piphp/gpio/v/stable)](https://packagist.org/packages/piphp/gpio)
[![Build Status](https://scrutinizer-ci.com/g/PiPHP/GPIO/badges/build.png?b=master)](https://scrutinizer-ci.com/g/PiPHP/GPIO/build-status/master)
[![Code Coverage](https://scrutinizer-ci.com/g/PiPHP/GPIO/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/PiPHP/GPIO/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/PiPHP/GPIO/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/PiPHP/GPIO/?branch=master)
[![License](https://poser.pugx.org/piphp/gpio/license)](https://packagist.org/packages/piphp/gpio)
[![Total Downloads](https://poser.pugx.org/piphp/gpio/downloads)](https://packagist.org/packages/piphp/gpio)

A library for low level access to the GPIO pins on a Raspberry Pi. These pins can be used to control outputs (LEDs, motors, valves, pumps) or read inputs (sensors).

By [AndrewCarterUK ![(Twitter)](http://i.imgur.com/wWzX9uB.png)](https://twitter.com/AndrewCarterUK)

## Installing

Using [composer](https://getcomposer.org/):

`composer require piphp/gpio`

Or:

`php composer.phar require piphp/gpio`

## Examples

### Setting Output Pins
```php
use PiPHP\GPIO\PinInterface;
use PiPHP\GPIO\PinFactory;

// Retrieve a pin object using the factory class
$pin = (new PinFactory)->getPin(18);

// Export the pin (so that it is available to use)
$pin->export();

// Set the pin as an output pin
$pin->setDirection(PinInterface::DIRECTION_OUT);

// Set the value of the pin high (turn it on)
$pin->setValue(PinInterface::VALUE_HIGH);
```

### Input Pin Interrupts
```php
use PiPHP\GPIO\InterruptWatcherFactory;
use PiPHP\GPIO\PinInterface;
use PiPHP\GPIO\PinFactory;

// Retrieve a pin object using the factory class
$pin = (new PinFactory)->getPin(18);

// Export the pin (so that it is available to use)
$pin->export();

// Set the pin as an input pin
$pin->setDirection(PinInterface::DIRECTION_IN);

// Configure the pin to trigger interrupts on both rising and falling edges
$pin->setEdge(PinInterface::EDGE_BOTH);

// Create an interrupt watcher using the factory class
$interruptWatcher = (new InterruptWatcherFactory)->createWatcher();

// Register a callback to be triggered on pin interrupts
$interruptWatcher->register($pin, function (PinInterface $pin, $value) {
    echo 'Pin ' . $pin->getNumber() . ' changed to: ' . $value . PHP_EOL;

    // Returning false will make the watcher return false immediately
    return true;
});

// Watch for interrupts, timeout after 5000ms (5 seconds)
while ($interruptWatcher->watch(5000));
```
