<?php

namespace App\Controllers;

use App\Services\MinifigureService;
use App\Repositories\OrderRepository;

class CheckoutController
{
    private MinifigureService $minifigureService;
    private OrderRepository $orderRepo;

    public function __construct()
    {
        $this->minifigureService = new MinifigureService();
        $this->orderRepo = new OrderRepository();
    }

    // GET /checkout
    public function index(array $parameters = []): void
    {
        $pageTitle = 'Checkout';

        if (!isset($_SESSION['user'])) {
    header('Location: /login');
    exit;
}
        $cart = $_SESSION['cart'] ?? [];
        if (empty($cart)) {
            header('Location: /cart');
            exit;
        }

        $cartItems = [];
        $totalCents = 0;

        foreach ($cart as $id => $qty) {
            $id = (int)$id;
            $qty = (int)$qty;

            $fig = $this->minifigureService->getById($id);
            if ($fig !== null) {
                $lineTotal = $fig->priceCents * $qty;
                $totalCents += $lineTotal;

                $cartItems[] = [
                    'minifigure' => $fig,
                    'quantity' => $qty,
                    'lineTotalCents' => $lineTotal
                ];
            }
        }

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

        $customerName = trim($_POST['customerName'] ?? '');
        $customerEmail = trim($_POST['customerEmail'] ?? '');

        if ($customerName === '' || $customerEmail === '') {
            $pageTitle = 'Checkout';
            $error = 'Please fill in name and email.';
            $contentView = __DIR__ . '/../Views/checkout/index.php';
            require __DIR__ . '/../Views/layout/main.php';
            return;
        }

        // rebuild cart items & totals (never trust only POST)
        $items = [];
        $totalCents = 0;

        foreach ($cart as $id => $qty) {
            $id = (int)$id;
            $qty = (int)$qty;

            $fig = $this->minifigureService->getById($id);
            if ($fig !== null) {
                $lineTotal = $fig->priceCents * $qty;
                $totalCents += $lineTotal;

                $items[] = [
                    'minifigureId' => $fig->id,
                    'quantity' => $qty,
                    'priceCents' => $fig->priceCents,
                    'lineTotalCents' => $lineTotal
                ];
            }
        }

        // Save order + order items
        $orderId = $this->orderRepo->createOrder($customerName, $customerEmail, $totalCents, $items);

        // Clear cart
        $_SESSION['cart'] = [];

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
