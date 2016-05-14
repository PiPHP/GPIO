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
use PiPHP\GPIO\GPIO;
use PiPHP\GPIO\Pin\PinInterface;

// Create a GPIO object
$gpio = new GPIO();

// Retrieve pin 18 and configure it as an output pin
$pin = $gpio->getOutputPin(18);

// Set the value of the pin high (turn it on)
$pin->setValue(PinInterface::VALUE_HIGH);
```

### Input Pin Interrupts
```php
use PiPHP\GPIO\GPIO;
use PiPHP\GPIO\Pin\InputPinInterface;

// Create a GPIO object
$gpio = new GPIO();

// Retrieve pin 18 and configure it as an input pin
$pin = $gpio->getInputPin(18);

// Configure interrupts for both rising and falling edges
$pin->setEdge(InputPinInterface::EDGE_BOTH);

// Create an interrupt watcher
$interruptWatcher = $gpio->createWatcher();

// Register a callback to be triggered on pin interrupts
$interruptWatcher->register($pin, function (InputPinInterface $pin, $value) {
    echo 'Pin ' . $pin->getNumber() . ' changed to: ' . $value . PHP_EOL;

    // Returning false will make the watcher return false immediately
    return true;
});

// Watch for interrupts, timeout after 5000ms (5 seconds)
while ($interruptWatcher->watch(5000));
```
