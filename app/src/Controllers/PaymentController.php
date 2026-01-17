<?php

namespace App\Controllers;

use App\Repositories\OrderRepository;

class PaymentController
{
    private OrderRepository $orderRepo;

    public function __construct()
    {
        $this->orderRepo = new OrderRepository();
    }

    // GET /payment/{orderId}
    public function index(array $parameters = []): void
    {
        $orderId = isset($parameters['id']) ? (int)$parameters['id'] : 0;

        if ($orderId === 0) {
            http_response_code(400);
            echo 'Invalid order ID';
            return;
        }

        $order = $this->orderRepo->getOrderById($orderId);
        if ($order === null) {
            http_response_code(404);
            echo 'Order not found';
            return;
        }

        // Check if order is already paid
        if ($order['status'] === 'paid') {
            header('Location: /order/' . $orderId);
            exit;
        }

        // Check access: logged-in user owns order OR guest who just placed it
        $isLoggedIn = isset($_SESSION['user']);
        $isOwnOrder = $isLoggedIn && (int)$order['userId'] === $_SESSION['user']['id'];
        $isJustPlacedGuest = !$isLoggedIn && isset($_SESSION['guestOrderId']) && $_SESSION['guestOrderId'] === $orderId;

        if (!$isOwnOrder && !$isJustPlacedGuest) {
            http_response_code(403);
            echo 'Access denied';
            return;
        }

        function euroFromCents(int $cents): string
        {
            return '€' . number_format($cents / 100, 2, '.', '');
        }

        $pageTitle = 'Payment';
        $totalEuros = euroFromCents((int)$order['totalCents']);
        $orderId = $orderId; // ensure available to view
        $contentView = __DIR__ . '/../Views/payment/index.php';
        require __DIR__ . '/../Views/layout/main.php';
    }

    // POST /payment/{orderId}
    public function processPayment(array $parameters = []): void
    {
        $orderId = isset($parameters['id']) ? (int)$parameters['id'] : 0;

        if ($orderId === 0) {
            http_response_code(400);
            echo 'Invalid order ID';
            return;
        }

        $order = $this->orderRepo->getOrderById($orderId);
        if ($order === null) {
            http_response_code(404);
            echo 'Order not found';
            return;
        }

        // Check access
        $isLoggedIn = isset($_SESSION['user']);
        $isOwnOrder = $isLoggedIn && (int)$order['userId'] === $_SESSION['user']['id'];
        $isJustPlacedGuest = !$isLoggedIn && isset($_SESSION['guestOrderId']) && $_SESSION['guestOrderId'] === $orderId;

        if (!$isOwnOrder && !$isJustPlacedGuest) {
            http_response_code(403);
            echo 'Access denied';
            return;
        }

        // Get card details (in real life, never handle these yourself!)
        $cardNumber = $_POST['cardNumber'] ?? '';
        $cvv = $_POST['cvv'] ?? '';

        // Basic validation
        if (empty($cardNumber) || empty($cvv)) {
            $error = 'Please fill in all payment details';
            $pageTitle = 'Payment';
            function euroFromCents(int $cents): string
            {
                return '€' . number_format($cents / 100, 2, '.', '');
            }
            $totalEuros = euroFromCents((int)$order['totalCents']);
            $orderId = $orderId;
            $contentView = __DIR__ . '/../Views/payment/index.php';
            require __DIR__ . '/../Views/layout/main.php';
            return;
        }

        // Simulate payment processing - 90% success rate
        $success = rand(1, 10) <= 10; // force success for demo

        if ($success) {
            // Update order status to 'paid'
            $this->orderRepo->updateOrderStatus($orderId, 'paid');

            // Clear guest order info if applicable
            if ($isJustPlacedGuest) {
                unset($_SESSION['guestOrderId']);
                unset($_SESSION['guestOrderEmail']);
            }

            // Redirect to confirmation page
            header('Location: /order/' . $orderId);
            exit;
        } else {
            // Payment failed - show error message
            $error = 'Payment declined. Please try again.';
            $pageTitle = 'Payment';
            function euroFromCents(int $cents): string
            {
                return '€' . number_format($cents / 100, 2, '.', '');
            }
            $totalEuros = euroFromCents((int)$order['totalCents']);
            $orderId = $orderId;
            $contentView = __DIR__ . '/../Views/payment/index.php';
            require __DIR__ . '/../Views/layout/main.php';
            return;
        }
    }
}
