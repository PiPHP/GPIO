# PiPHP: GPIO

[![Latest Stable Version](https://poser.pugx.org/piphp/gpio/v/stable)](https://packagist.org/packages/piphp/gpio)
[![Build Status](https://scrutinizer-ci.com/g/PiPHP/GPIO/badges/build.png?b=master)](https://scrutinizer-ci.com/g/PiPHP/GPIO/build-status/master)
[![Code Coverage](https://scrutinizer-ci.com/g/PiPHP/GPIO/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/PiPHP/GPIO/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/PiPHP/GPIO/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/PiPHP/GPIO/?branch=master)
[![License](https://poser.pugx.org/piphp/gpio/license)](https://packagist.org/packages/piphp/gpio)
[![Total Downloads](https://poser.pugx.org/piphp/gpio/downloads)](https://packagist.org/packages/piphp/gpio)

A library for low level access to the GPIO pins on a Raspberry Pi

## Examples

## Setting Output Pins
```php
use PiPHP\GPIO\PinInterface;
use PiPHP\GPIO\PinFactory;

$pin = (new PinFactory)->getPin(18);
$pin->export();
$pin->setDirection(PinInterface::DIRECTION_OUT);
$pin->setValue(PinInterface::VALUE_HIGH);
```

## Input Pin Interrupts
```php
use PiPHP\GPIO\InterruptWatcherFactory;
use PiPHP\GPIO\PinInterface;
use PiPHP\GPIO\PinFactory;

$pin = (new PinFactory)->getPin(18);
$pin->export();
$pin->setEdge(PinInterface::EDGE_BOTH);
$pin->setDirection(PinInterface::DIRECTION_IN);
$pin->setValue(PinInterface::VALUE_HIGH);

$interruptWatcher = (new InterruptWatcherFactory)->createWatcher();
$interruptWatcher->addPin($pin, function (PinInterface $pin, $value) {
    echo 'Pin ' . $pin->getNumber() . ' changed to: ' . $value . PHP_EOL;
});

while (1) {
  $interruptWatcher->watch(5);
}
```
