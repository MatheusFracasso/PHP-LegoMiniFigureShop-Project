<?php

namespace App\Services;

// Order creation logic
interface IOrderService
{
    // Create order from cart, returns order ID or error array
    public function createOrderFromCart(?int $userId, string $customerName, string $customerEmail): int|array;
}
