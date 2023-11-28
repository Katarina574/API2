<?php

$loader = new \Phalcon\Autoload\Loader();

/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->setDirectories(
    [
        $config->application->controllersDir,
        $config->application->modelsDir
    ]
)->register();

$loader->setNamespaces(
    [
        'App\Repositories' => APP_PATH . '/repositories/',
        'App\Services' => APP_PATH . '/services/',
    ]
);
