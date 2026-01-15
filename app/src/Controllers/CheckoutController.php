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

        $cart = $_SESSION['cart'] ?? [];
        if (empty($cart)) {
            header('Location: /cart');
            exit;
        }

        $cartData = $this->cartService->getCartWithTotals($cart);
        $cartItems = $cartData['items'];
        $totalCents = $cartData['totalCents'];

        $isLoggedIn = isset($_SESSION['user']);
        $customerEmail = $isLoggedIn ? $_SESSION['user']['email'] : '';
        $customerName = $isLoggedIn ? $_SESSION['user']['name'] : '';

        $contentView = __DIR__ . '/../Views/checkout/index.php';
        require __DIR__ . '/../Views/layout/main.php';
    }

    // POST /checkout
    public function placeOrder(array $parameters = []): void
    {
        $cart = $_SESSION['cart'] ?? [];
        if (empty($cart)) {
            header('Location: /cart');
            exit;
        }

        $customerName = $_POST['customerName'] ?? '';
        $customerEmail = $_POST['customerEmail'] ?? '';
        $userId = isset($_SESSION['user']) ? $_SESSION['user']['id'] : null;

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
            $isLoggedIn = isset($_SESSION['user']);
            $contentView = __DIR__ . '/../Views/checkout/index.php';
            require __DIR__ . '/../Views/layout/main.php';
            return;
        }

        // Success case - result is the orderId (int)
        $orderId = $result;

        // If guest checkout, store order info in session for one-time display
        if (!isset($_SESSION['user'])) {
            $_SESSION['guestOrderId'] = $orderId;
            $_SESSION['guestOrderEmail'] = $customerEmail;
        }

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

        // Check if user is logged in or if this is a guest order they just placed
        $isLoggedIn = isset($_SESSION['user']);
        $isGuestOrder = (int)$order['userId'] === 0 || $order['userId'] === null;
        $isOwnOrder = $isLoggedIn && (int)$order['userId'] === $_SESSION['user']['id'];
        $isJustPlacedGuest = !$isLoggedIn && isset($_SESSION['guestOrderId']) && $_SESSION['guestOrderId'] === $id;

        if ($isGuestOrder && !$isJustPlacedGuest) {
            http_response_code(403);
            echo 'Order details not available';
            return;
        }

        if (!$isOwnOrder && !$isJustPlacedGuest) {
            http_response_code(403);
            echo 'Order details not available';
            return;
        }

        // Clear guest order info after displaying once
        if ($isJustPlacedGuest) {
            unset($_SESSION['guestOrderId']);
            unset($_SESSION['guestOrderEmail']);
        }

        $pageTitle = 'Order Confirmation';
        $isGuest = !$isLoggedIn;
        $contentView = __DIR__ . '/../Views/checkout/confirmation.php';
        require __DIR__ . '/../Views/layout/main.php';
    }
}
