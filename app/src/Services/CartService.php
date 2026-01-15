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

    public function getCartWithTotals(array $sessionCart): array
    {
        $cartItems = [];
        $totalCents = 0;

        foreach ($sessionCart as $id => $qty) {
            $minifigure = $this->minifigureRepo->getById((int)$id);

            if ($minifigure !== null) {
                $qty = (int)$qty;
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

    public function addToCart(int $productId): void
    {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (!isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId] = 0;
        }

        $_SESSION['cart'][$productId]++;
    }

    public function removeFromCart(int $productId): void
    {
        if (isset($_SESSION['cart'][$productId])) {
            unset($_SESSION['cart'][$productId]);
        }
    }

    public function updateQuantity(int $productId, int $quantity): void
    {
        if ($quantity <= 0) {
            if (isset($_SESSION['cart'][$productId])) {
                unset($_SESSION['cart'][$productId]);
            }
        } else {
            $_SESSION['cart'][$productId] = $quantity;
        }
    }

    public function clearCart(): void
    {
        $_SESSION['cart'] = [];
    }
}
