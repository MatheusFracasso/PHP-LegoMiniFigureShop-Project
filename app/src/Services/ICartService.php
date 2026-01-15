<?php

namespace App\Services;

interface ICartService
{
    /**
     * Get cart items enriched with product data and calculated totals.
     *
     * @param array $sessionCart The cart array from $_SESSION['cart']
     * @return array Array with 'items' and 'totalCents' (in cents)
     */
    public function getCartWithTotals(array $sessionCart): array;

    /**
     * Add a product to the cart (or increment quantity if already present).
     *
     * @param int $productId
     */
    public function addToCart(int $productId): void;

    /**
     * Remove a product from the cart.
     *
     * @param int $productId
     */
    public function removeFromCart(int $productId): void;

    /**
     * Update quantity for a product. If quantity is 0 or negative, removes the item.
     *
     * @param int $productId
     * @param int $quantity
     */
    public function updateQuantity(int $productId, int $quantity): void;

    /**
     * Clear the entire cart.
     */
    public function clearCart(): void;
}
