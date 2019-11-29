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
    'tomatoes/',
    ['controller' => 'StaticFiles', 'action' => 'tomatoes']
);

// In a production environment this should be a POST
// but since this is just an example, I use this to demonstrate
// how to pass parameters to your routes.
$router->add(
    'custom/{id:\d+}/{leaf:[a-f0-9]+}/{core:[a-f0-9]+}/{weight:\d+}/',
    ['controller' => 'StaticFiles', 'action' => 'customTomato']
);

$router->add(
    '',
    ['controller' => 'Home', 'action' => 'index']
);

// Add more routes here

/**
 * Convert URI to QueryString
 */
preg_match_all(
    '/^\/?(.*?)\??((?:&?\w+\=?\w+)*)$/i',
    $_SERVER['REQUEST_URI'],
    $matches,
    PREG_SET_ORDER,
    0
);

$QueryString = $matches[0][1]; // Group 1: Controller/Action
if ($matches[0][2] != '') {
    $QueryString .= "&" . $matches[0][2]; // Group 2: params
}

// Send the query string to the router
$router->dispatch($QueryString, $_SERVER['REQUEST_METHOD']);
