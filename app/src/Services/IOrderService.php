<?php

namespace App\Services;

use App\Repositories\OrderRepository;

interface IOrderService
{
    /**
     * Create an order from the current session cart.
     * Validates input, rebuilds cart, creates order, clears cart.
     *
     * @param int $userId The user ID placing the order
     * @param string $customerName Customer name (from form)
     * @param string $customerEmail Customer email (from form)
     * @return int|array The order ID on success, or error array ['error' => 'message'] on failure
     */
    public function createOrderFromCart(int $userId, string $customerName, string $customerEmail): int|array;
}
