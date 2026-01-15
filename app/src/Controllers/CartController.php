<?php

namespace App\Controllers;

use App\Services\CartService;

class CartController
{
    private CartService $cartService;

    public function __construct()
    {
        $this->cartService = new CartService();
    }

    // GET /cart
    public function index(array $parameters = []): void
    {
        $pageTitle = 'Your Cart';

        $cart = $_SESSION['cart'] ?? [];
        $cartData = $this->cartService->getCartWithTotals($cart);
        $cartItems = $cartData['items'];
        $totalCents = $cartData['totalCents'];

        $contentView = __DIR__ . '/../Views/cart/index.php';
        require __DIR__ . '/../Views/layout/main.php';
    }

    // POST /cart/add/{id}
    public function add(array $parameters = []): void
    {
        $id = isset($parameters['id']) ? (int)$parameters['id'] : 0;

        $this->cartService->addToCart($id);

        header('Location: /cart');
        exit;
    }

    // POST /cart/remove/{id}
    public function remove(array $parameters = []): void
    {
        $id = isset($parameters['id']) ? (int)$parameters['id'] : 0;

        $this->cartService->removeFromCart($id);

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

        $this->cartService->updateQuantity($id, $qty);

        header('Location: /cart');
        exit;
    }
}
