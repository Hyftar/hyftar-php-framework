<?php

/**
* Composer
*/
require dirname(__DIR__) . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::create(__DIR__ . '/../');
$dotenv->load();

/**
 * Starts user sessions
 */
session_start();

$renderer = new Core\Renderer();

/**
 * Error and Exception handling
 */

$error_handler = new Core\ErrorHandler($renderer);
error_reporting(E_ALL);
set_error_handler([$error_handler, 'handleError']);
set_exception_handler([$error_handler, 'handleException']);

/**
 * Routing
 */
$router = new Core\Router($renderer);

$router->get(
    'tomatoes',
    ['controller' => 'Tomatoes', 'action' => 'tomatoes']
);

// In a real project this should be a POST request
// but since this is just an example, I use this to demonstrate
// how to add path parameters to your routes.
$router->get(
    'custom/{id:/\d+/}/{leaf:/[a-f0-9]{3}|[a-f0-9]{6}/}/{core:/[a-f0-9]{3}|[a-f0-9]{6}/}/{weight:/\d+/}',
    ['controller' => 'Tomatoes', 'action' => 'customTomato']
);

$router->get(
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
