<?php
session_start();

/**
 * This is the central route handler of the application.
 * It uses FastRoute to map URLs to controller methods.
 * 
 * See the documentation for FastRoute for more information: https://github.com/nikic/FastRoute
 */

require __DIR__ . '/../vendor/autoload.php';//FastRoute available

//Session for cart/login
if (session_status()===PHP_SESSION_NONE){
    session_start();
}

use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

/**
 * Define the routes for the application.
 */
$dispatcher = simpleDispatcher(function (RouteCollector $r) {
    $r->addRoute('GET', '/', ['App\Controllers\HomeController', 'home']);
    $r->addRoute('GET', '/minifigures', ['App\Controllers\MinifigureController', 'index']);//all minifigures
    $r->addRoute('GET', '/minifigures/{id:\d+}', ['App\Controllers\MinifigureController', 'detail']);//specific, in detail
    $r->addRoute('GET',  '/cart',               ['App\Controllers\CartController', 'index']);
    $r->addRoute('POST', '/cart/add/{id:\d+}',  ['App\Controllers\CartController', 'add']);
    $r->addRoute('POST', '/cart/remove/{id:\d+}', ['App\Controllers\CartController', 'remove']);
    $r->addRoute('POST', '/cart/update/{id:\d+}', ['App\Controllers\CartController', 'update']);
    $r->addRoute('GET',  '/checkout', ['App\Controllers\CheckoutController', 'index']);
    $r->addRoute('POST', '/checkout', ['App\Controllers\CheckoutController', 'placeOrder']);
    $r->addRoute('GET',  '/order/{id:\d+}', ['App\Controllers\CheckoutController', 'confirmation']);
    $r->addRoute('GET',  '/register', ['App\Controllers\AuthorController', 'showRegister']);
    $r->addRoute('POST', '/register', ['App\Controllers\AuthorController', 'register']);

    $r->addRoute('GET',  '/login', ['App\Controllers\AuthorController', 'showLogin']);
    $r->addRoute('POST', '/login', ['App\Controllers\AuthorController', 'login']);

    $r->addRoute('POST', '/logout', ['App\Controllers\AuthorController', 'logout']);

});
// Get the request method and URI from the server variables and invoke the dispatcher
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = strtok($_SERVER['REQUEST_URI'], '?');

//ask the router to find a matching route
$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

// Switch on the dispatcher result and call the appropriate controller method if found
switch ($routeInfo[0]) {
    // Handle not found routes
    case FastRoute\Dispatcher::NOT_FOUND:
        http_response_code(404);
        echo 'Not Found';
        break;
    // Handle routes that were invoked with the wrong HTTP method
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        http_response_code(405);
        echo 'Method Not Allowed';
        break;
    // Handle found routes
    case FastRoute\Dispatcher::FOUND:
    //routeInfo[1] = ['App\Controllers\HomeController', 'home']
    $className = $routeInfo[1][0];
    $methodName = $routeInfo[1][1];

    //Route parameters  /hello{name}
    $parameters =$routeInfo[2];

    //Controller class dynamic initiciation
    $controller = new $className();

    //call the method with route parameters
    $controller->$methodName($parameters);
        break;
}
