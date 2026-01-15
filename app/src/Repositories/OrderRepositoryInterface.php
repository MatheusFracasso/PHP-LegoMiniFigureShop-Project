<?php

namespace App\Repositories;

interface OrderRepositoryInterface
{
    /**
     * Create a new order with items.
     *
     * @param string $customerName
     * @param string $customerEmail
     * @param int $totalCents Total price in cents
     * @param array<int, array<string,int>> $items Order items with minifigureId, quantity, priceCents, lineTotalCents
     * @param int|null $userId Optional user ID who placed the order
     * @return int The ID of the newly created order
     */
    public function createOrder(string $customerName, string $customerEmail, int $totalCents, array $items, ?int $userId = null): int;

    /**
     * Get a single order by ID with all its items.
     *
     * @param int $id The order ID
     * @return array|null The order data or null if not found
     */
    public function getOrderById(int $id): ?array;

    /**
     * Get all orders.
     *
     * @return array List of orders
     */
    public function getAllOrders(): array;

    /**
     * Get all orders for a specific user.
     *
     * @param int $userId The user ID
     * @return array List of orders for that user
     */
    public function getOrdersByUserId(int $userId): array;

    /**
     * Update the status of an order.
     *
     * @param int $id The order ID
     * @param string $status New status (pending, shipped, delivered, cancelled)
     * @return bool True if update was successful, false otherwise
     */
    public function updateOrderStatus(int $id, string $status): bool;

    /**
     * Delete an order and all its items.
     *
     * @param int $id The order ID
     * @return bool True if deletion was successful, false otherwise
     */
    public function deleteOrder(int $id): bool;
}
