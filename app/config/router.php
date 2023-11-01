<?php

$router = $di->getRouter();

// Define your routes here
$router->add('/api', [
    'controller' => 'Test',
    'action' => 'index'
])->via(['GET']);

$router->handle($_SERVER['REQUEST_URI']);
