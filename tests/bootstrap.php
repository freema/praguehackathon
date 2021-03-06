<?php

// Load Nette Framework or autoloader generated by Composer
require __DIR__ . '/../vendor/autoload.php';

$configurator = new Nette\Config\Configurator;

// Enable Nette Debugger for error visualisation & logging
$configurator->setDebugMode(TRUE);
@mkdir(__DIR__ . '/log', 0755, true);
$configurator->enableDebugger(__DIR__ . '/log');

// Specify folder for cache
@mkdir(__DIR__ . '/temp', 0755, true);
$configurator->setTempDirectory(__DIR__ . '/temp');

// Enable RobotLoader - this will load all classes automatically
$configurator   ->createRobotLoader()
                ->addDirectory(__DIR__)
                ->register();

// Create Dependency Injection container from config.neon file
return $configurator->createContainer();