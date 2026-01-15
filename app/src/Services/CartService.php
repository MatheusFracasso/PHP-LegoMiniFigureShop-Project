<?php

namespace App\Services;

use App\Repositories\MiniFigureRepository;

class CartService implements ICartService
{
    private MiniFigureRepository $minifigureRepo;

    public function __construct()
    {
        $this->minifigureRepo = new MiniFigureRepository();
    }

    /**
     * Get cart items enriched with product data and calculated totals.
     */
    public function getCartWithTotals(array $sessionCart): array
    {
        $cartItems = [];
        $totalCents = 0;

        // Build a list of items with product info + quantity
        foreach ($sessionCart as $id => $qty) {
            $id = (int)$id;
            $qty = (int)$qty;

            $minifigure = $this->minifigureRepo->getById($id);

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

        return [
            'items' => $cartItems,
            'totalCents' => $totalCents
        ];
    }

    /**
     * Add a product to the cart (or increment quantity if already present).
     */
    public function addToCart(int $productId): void
    {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (!isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId] = 0;
        }

        $_SESSION['cart'][$productId] = $_SESSION['cart'][$productId] + 1;
    }

    /**
     * Remove a product from the cart.
     */
    public function removeFromCart(int $productId): void
    {
        if (isset($_SESSION['cart'][$productId])) {
            unset($_SESSION['cart'][$productId]);
        }
    }

    /**
     * Update the quantity of a product in the cart.
     * If quantity is 0 or negative, the item is removed.
     */
    public function updateQuantity(int $productId, int $quantity): void
    {
        if ($quantity <= 0) {
            // 0 or negative = remove
            if (isset($_SESSION['cart'][$productId])) {
                unset($_SESSION['cart'][$productId]);
            }
        } else {
            $_SESSION['cart'][$productId] = $quantity;
        }
    }

    /**
     * Clear the entire cart.
     */
    public function clearCart(): void
    {
        $_SESSION['cart'] = [];
    }
}
