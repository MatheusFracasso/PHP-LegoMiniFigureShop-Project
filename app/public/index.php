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
  // Home
  $r->addRoute('GET', '/', ['App\Controllers\HomeController', 'home']);

  // Minifigures
  $r->addRoute('GET', '/minifigures', ['App\Controllers\MinifigureController', 'index']);
  $r->addRoute('GET', '/minifigures/{id:\d+}', ['App\Controllers\MinifigureController', 'detail']);
  $r->addRoute('GET', '/shop', ['App\Controllers\MinifigureController', 'index']);

  // Cart
  $r->addRoute('GET', '/cart', ['App\Controllers\CartController', 'index']);
  $r->addRoute('POST', '/cart/add/{id:\d+}', ['App\Controllers\CartController', 'add']);
  $r->addRoute('POST', '/cart/remove/{id:\d+}', ['App\Controllers\CartController', 'remove']);
  $r->addRoute('POST', '/cart/update/{id:\d+}', ['App\Controllers\CartController', 'update']);

  // Checkout
  $r->addRoute('GET', '/checkout', ['App\Controllers\CheckoutController', 'index']);
  $r->addRoute('POST', '/checkout', ['App\Controllers\CheckoutController', 'placeOrder']);
  $r->addRoute('GET', '/order/{id:\d+}', ['App\Controllers\CheckoutController', 'confirmation']);

  // Payment
  $r->addRoute('GET', '/payment/{id:\d+}', ['App\Controllers\PaymentController', 'index']);
  $r->addRoute('POST', '/payment/{id:\d+}', ['App\Controllers\PaymentController', 'processPayment']);

  // Authorization
  $r->addRoute('GET', '/register', ['App\Controllers\AuthorizationController', 'showRegister']);
  $r->addRoute('POST', '/register', ['App\Controllers\AuthorizationController', 'register']);
  $r->addRoute('GET', '/login', ['App\Controllers\AuthorizationController', 'showLogin']);
  $r->addRoute('POST', '/login', ['App\Controllers\AuthorizationController', 'login']);
  $r->addRoute('POST', '/logout', ['App\Controllers\AuthorizationController', 'logout']);

  // User Account
  $r->addRoute('GET', '/my-orders', ['App\Controllers\UserOrderController', 'myOrders']);

  // Admin dashboard
$r->addRoute('GET', '/admin', ['App\Controllers\AdminController', 'dashboard']);
$r->addRoute('GET', '/admin/dashboard', ['App\Controllers\AdminController', 'dashboard']);

// Admin minifigures CRUD
$r->addRoute('GET',  '/admin/minifigures', ['App\Controllers\AdminMinifigureController', 'index']);
$r->addRoute('GET',  '/admin/minifigures/create', ['App\Controllers\AdminMinifigureController', 'create']);
$r->addRoute('POST', '/admin/minifigures/create', ['App\Controllers\AdminMinifigureController', 'store']);
$r->addRoute('GET',  '/admin/minifigures/edit/{id:\d+}', ['App\Controllers\AdminMinifigureController', 'edit']);
$r->addRoute('POST', '/admin/minifigures/edit/{id:\d+}', ['App\Controllers\AdminMinifigureController', 'update']);
$r->addRoute('POST', '/admin/minifigures/delete/{id:\d+}', ['App\Controllers\AdminMinifigureController', 'delete']);

// Admin orders
$r->addRoute('GET', '/admin/orders', ['App\Controllers\AdminOrderController', 'index']);
$r->addRoute('GET', '/admin/orders/{id:\d+}', ['App\Controllers\AdminOrderController', 'detail']);
$r->addRoute('POST', '/admin/orders/{id:\d+}/status', ['App\Controllers\AdminOrderController', 'updateStatus']);
$r->addRoute('POST', '/admin/orders/{id:\d+}/delete', ['App\Controllers\AdminOrderController', 'delete']);

// Admin users (role switching)
$r->addRoute('GET',  '/admin/users', ['App\Controllers\AdminUserController', 'index']);
$r->addRoute('POST', '/admin/users/role/{id:\d+}', ['App\Controllers\AdminUserController', 'changeRole']);

// API
$r->addRoute('GET', '/api/minifigures', ['App\Controllers\Api\MinifigureApiController', 'index']);

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
