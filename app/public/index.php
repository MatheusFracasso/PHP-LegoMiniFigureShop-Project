<?php
session_start();

require __DIR__ . '/../vendor/autoload.php';

// Double-check session is started
if (session_status()===PHP_SESSION_NONE){
    session_start();
}

use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

// Setup routes
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

// Get request info
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = strtok($_SERVER['REQUEST_URI'], '?');

// Find matching route
$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        http_response_code(404);
        echo 'Not Found';
        break;
        
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        http_response_code(405);
        echo 'Method Not Allowed';
        break;
        
    case FastRoute\Dispatcher::FOUND:
        // Get controller and method from route
        $className = $routeInfo[1][0];
        $methodName = $routeInfo[1][1];
        $parameters = $routeInfo[2]; // URL params like {id}
        
        // Create controller and call the method
        $controller = new $className();
        $controller->$methodName($parameters);
        break;
}
