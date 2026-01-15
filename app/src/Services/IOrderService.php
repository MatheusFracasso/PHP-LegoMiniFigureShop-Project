<?php

namespace App\Services;

interface IOrderService
{
    /**
     * Create an order from the current session cart.
     * Returns order ID on success, error array on failure.
     *
     * @param int $userId
     * @param string $customerName
     * @param string $customerEmail
     * @return int|array Order ID or ['error' => 'message']
     */
    public function createOrderFromCart(int $userId, string $customerName, string $customerEmail): int|array;
}
