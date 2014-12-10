<?php

use Symfony\Component\Config\FileLocator;

require_once __DIR__.'/vendor/autoload.php';

$configDirectories = array(__DIR__.'/app/config');

$locator = new FileLocator($configDirectories);
$yamlUserFiles = $locator->locate('config.yml', null, false);
die(var_dump($yamlUserFiles));
