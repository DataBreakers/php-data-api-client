<?php

$loader = include __DIR__ . '/../vendor/autoload.php';
$loader->addPsr4('DataBreakers\\', __DIR__);

// Configure environment
Tester\Environment::setup();
date_default_timezone_set('Europe/Prague');

// Create temporary directory
define('TEMP_DIR', __DIR__ . '/temp/' . getmypid());
Tester\Helpers::purge(TEMP_DIR);
