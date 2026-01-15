<?php

namespace App\Services;

use App\Models\MiniFigure;

interface ICartService
{
    /**
     * Get cart items enriched with product data and calculated totals.
     *
     * @param array $sessionCart The cart array from $_SESSION['cart']
     * @return array Array with 'items' (list of items with product data), 'totalCents' (sum of all line totals)
     */
    public function getCartWithTotals(array $sessionCart): array;

    /**
     * Add a product to the cart (or increment quantity if already present).
     *
     * @param int $productId The minifigure ID to add
     * @return void Modifies $_SESSION['cart'] directly
     */
    public function addToCart(int $productId): void;

    /**
     * Remove a product from the cart.
     *
     * @param int $productId The minifigure ID to remove
     * @return void Modifies $_SESSION['cart'] directly
     */
    public function removeFromCart(int $productId): void;

    /**
     * Update the quantity of a product in the cart.
     * If quantity is 0 or negative, the item is removed.
     *
     * @param int $productId The minifigure ID
     * @param int $quantity New quantity (0 or negative = remove)
     * @return void Modifies $_SESSION['cart'] directly
     */
    public function updateQuantity(int $productId, int $quantity): void;

    /**
     * Clear the entire cart.
     *
     * @return void Modifies $_SESSION['cart'] directly
     */
    public function clearCart(): void;
}
