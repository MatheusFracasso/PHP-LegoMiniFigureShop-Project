<?php

namespace App\Controllers;

use App\Services\CartService;
use App\Services\OrderService;
use App\Repositories\OrderRepository;

class CheckoutController
{
    private CartService $cartService;
    private OrderService $orderService;
    private OrderRepository $orderRepo;

    public function __construct()
    {
        $this->cartService = new CartService();
        $this->orderService = new OrderService();
        $this->orderRepo = new OrderRepository();
    }

    // GET /checkout
    public function index(array $parameters = []): void
    {
        $pageTitle = 'Checkout';

        if (!isset($_SESSION['user'])) {
            $_SESSION['returnUrl'] = '/checkout';
            header('Location: /login');
            exit;
        }

        $cart = $_SESSION['cart'] ?? [];
        if (empty($cart)) {
            header('Location: /cart');
            exit;
        }

        $cartData = $this->cartService->getCartWithTotals($cart);
        $cartItems = $cartData['items'];
        $totalCents = $cartData['totalCents'];

        $customerEmail = $_SESSION['user']['email'];
        $customerName = $_SESSION['user']['name'];

        $contentView = __DIR__ . '/../Views/checkout/index.php';
        require __DIR__ . '/../Views/layout/main.php';
    }

    // POST /checkout
    public function placeOrder(array $parameters = []): void
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        $cart = $_SESSION['cart'] ?? [];
        if (empty($cart)) {
            header('Location: /cart');
            exit;
        }

        $customerName = $_POST['customerName'] ?? '';
        $customerEmail = $_POST['customerEmail'] ?? '';
        $userId = $_SESSION['user']['id'];

        // Delegate all order creation logic to service
        $result = $this->orderService->createOrderFromCart($userId, $customerName, $customerEmail);

        // Check if order creation was successful
        if (is_array($result)) {
            // Error case - result is ['error' => 'message']
            $pageTitle = 'Checkout';
            $error = $result['error'];
            $cartData = $this->cartService->getCartWithTotals($cart);
            $cartItems = $cartData['items'];
            $totalCents = $cartData['totalCents'];
            $contentView = __DIR__ . '/../Views/checkout/index.php';
            require __DIR__ . '/../Views/layout/main.php';
            return;
        }

        // Success case - result is the orderId (int)
        $orderId = $result;
        header('Location: /order/' . $orderId);
        exit;
    }

    // GET /order/{id}
    public function confirmation(array $parameters = []): void
    {
        $id = isset($parameters['id']) ? (int)$parameters['id'] : 0;

        $order = $this->orderRepo->getOrderById($id);
        if ($order === null) {
            http_response_code(404);
            echo 'Order not found';
            return;
        }

        $pageTitle = 'Order Confirmation';
        $contentView = __DIR__ . '/../Views/checkout/confirmation.php';
        require __DIR__ . '/../Views/layout/main.php';
    }
}
