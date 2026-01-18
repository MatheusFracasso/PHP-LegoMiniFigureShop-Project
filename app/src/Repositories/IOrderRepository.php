<?php

namespace App\Repositories;

// Order database operations
interface IOrderRepository
{
    // Create new order with items, returns order ID
    public function createOrder(string $customerName, string $customerEmail, int $totalCents, array $items, ?int $userId = null): int;

    // Get order by ID including all items
    public function getOrderById(int $id): ?array;

    // Get all orders
    public function getAllOrders(): array;

    // Get orders for specific user
    public function getOrdersByUserId(int $userId): array;

    // Update order status (pending, paid, shipped, etc)
    public function updateOrderStatus(int $id, string $status): bool;

    // Delete order and its items
    public function deleteOrder(int $id): bool;
}
