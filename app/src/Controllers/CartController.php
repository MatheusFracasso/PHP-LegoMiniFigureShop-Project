<?php

namespace App\Controllers;

use App\Services\MinifigureService;

class CartController
{
    private MinifigureService $minifigureService;

    public function __construct()
    {
        $this->minifigureService = new MinifigureService();
    }

    // GET /cart
    public function index(array $parameters = []): void
    {
        $pageTitle = 'Your Cart';

        $cart = $_SESSION['cart'] ?? [];
        $cartItems = [];
        $totalCents = 0;

        // Build a list of items with product info + quantity
        foreach ($cart as $id => $qty) {
            $id = (int)$id;
            $qty = (int)$qty;

            $minifigure = $this->minifigureService->getById($id);

            if ($minifigure !== null) {
                $lineTotal = $minifigure->priceCents * $qty;
                $totalCents += $lineTotal;

                $cartItems[] = [
                    'minifigure' => $minifigure,
                    'quantity' => $qty,
                    'lineTotalCents' => $lineTotal
                ];
            }
        }

        $contentView = __DIR__ . '/../Views/cart/index.php';
        require __DIR__ . '/../Views/layout/main.php';
    }

    // POST /cart/add/{id}
    public function add(array $parameters = []): void
    {
        $id = isset($parameters['id']) ? (int)$parameters['id'] : 0;

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (!isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id] = 0;
        }

        $_SESSION['cart'][$id] = $_SESSION['cart'][$id] + 1;

        header('Location: /cart');
        exit;
    }

    // POST /cart/remove/{id}
    public function remove(array $parameters = []): void
    {
        $id = isset($parameters['id']) ? (int)$parameters['id'] : 0;

        if (isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
        }

        header('Location: /cart');
        exit;
    }

    // POST /cart/update/{id}
    public function update(array $parameters = []): void
    {
        $id = isset($parameters['id']) ? (int)$parameters['id'] : 0;

        $qty = 0;
        if (isset($_POST['quantity'])) {
            $qty = (int)$_POST['quantity'];
        }

        if ($qty <= 0) {
            // 0 or negative = remove
            if (isset($_SESSION['cart'][$id])) {
                unset($_SESSION['cart'][$id]);
            }
        } else {
            $_SESSION['cart'][$id] = $qty;
        }

        header('Location: /cart');
        exit;
    }
}
