<?php

namespace App\Services;

// Shopping cart operations
interface ICartService
{
    // Get cart with totals calculated
    public function getCartWithTotals(array $sessionCart): array;

    // Add item to cart (or increase qty)
    public function addToCart(int $productId): void;

    // Remove item from cart
    public function removeFromCart(int $productId): void;

    // Update quantity (if 0 or negative, removes item)
    public function updateQuantity(int $productId, int $quantity): void;

    // Clear entire cart
    public function clearCart(): void;
}
