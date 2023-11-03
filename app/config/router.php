<?php

$router = $di->getRouter();

// Define your routes here
$router->add('/api', [
    'controller' => 'Api',
    'action' => 'index'
])->via(['GET']);


$router->add('/upload', [
    'controller' => 'Upload',
    'action' => 'index'
])->via(['POST']);


$router->add(
    '/test',
    [
        'controller' => 'Test',
        'action'     => 'index',
    ]
);

$router->handle($_SERVER['REQUEST_URI']);
