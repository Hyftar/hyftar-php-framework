<?php

/**
 * Starts user sessions
 */

session_start();

/**
 * Composer
 */
require dirname(__DIR__) . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::create(__DIR__ . '/../');
$dotenv->load();

/**
 * Error and Exception handling
 */
error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');

/**
 * Routing
 */
$router = new Core\Router();

$router->add(
    'tomatoes',
    ['controller' => 'Tomatoes', 'action' => 'tomatoes']
);

// In a real project this should be a POST request
// but since this is just an example, I use this to demonstrate
// how to add path parameters to your routes.
$router->add(
    'custom/{id:\d+}/{leaf:[a-f0-9]+}/{core:[a-f0-9]+}/{weight:\d+}',
    ['controller' => 'Tomatoes', 'action' => 'customTomato']
);

$router->add(
    '',
    [
        'controller' => 'Home',
        'action' => 'index',
        'allowed_variables' => ['id', 'page']
    ]
);

// Add more routes here

// Send the URI and Method to the dispatcher
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
