<?php

namespace App\Repositories;

use App\Config;
use PDO;
use PDOException;

class OrderRepository implements IOrderRepository
{
    private PDO $connection;

    public function __construct()
    {
        $dsn = 'mysql:host=' . Config::DB_SERVER_NAME .
               ';dbname=' . Config::DB_NAME .
               ';charset=utf8mb4';

        $this->connection = new PDO($dsn, Config::DB_USERNAME, Config::DB_PASSWORD);
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * @param array<int, array<string,int>> $items
     */
    public function createOrder(string $customerName, string $customerEmail, int $totalCents, array $items, ?int $userId = null): int
    {
        try {
            $this->connection->beginTransaction();

            // Insert into orders
            $stmt = $this->connection->prepare('
                INSERT INTO orders (userId, customerName, customerEmail, totalCents)
                VALUES (:userId, :customerName, :customerEmail, :totalCents)
            ');
            $stmt->execute([
                'userId' => $userId,
                'customerName' => $customerName,
                'customerEmail' => $customerEmail,
                'totalCents' => $totalCents
            ]);

            $orderId = (int)$this->connection->lastInsertId();

            // Insert items
            $itemStmt = $this->connection->prepare('
                INSERT INTO orderItems (orderId, minifigureId, quantity, priceCents, lineTotalCents)
                VALUES (:orderId, :minifigureId, :quantity, :priceCents, :lineTotalCents)
            ');

            foreach ($items as $item) {
                $itemStmt->execute([
                    'orderId' => $orderId,
                    'minifigureId' => $item['minifigureId'],
                    'quantity' => $item['quantity'],
                    'priceCents' => $item['priceCents'],
                    'lineTotalCents' => $item['lineTotalCents']
                ]);
            }

            $this->connection->commit();
            return $orderId;

        } catch (PDOException $e) {
            $this->connection->rollBack();
            die('Order save failed: ' . $e->getMessage());
        }
    }

    public function getOrderById(int $id): ?array
    {
        $stmt = $this->connection->prepare('SELECT * FROM orders WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($order === false) {
            return null;
        }

        $itemsStmt = $this->connection->prepare('
            SELECT oi.*, m.name
            FROM orderItems oi
            LEFT JOIN minifigures m ON oi.minifigureId = m.id
            WHERE oi.orderId = :id
        ');
        $itemsStmt->execute(['id' => $id]);
        $items = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);

        $order['items'] = $items;
        return $order;
    }

    public function getAllOrders(): array
    {
        $stmt = $this->connection->prepare('SELECT id, customerName, customerEmail, totalCents, status, createdAt FROM orders ORDER BY createdAt DESC');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOrdersByUserId(int $userId): array
    {
        $stmt = $this->connection->prepare('SELECT id, customerName, customerEmail, totalCents, status, createdAt FROM orders WHERE userId = :userId ORDER BY createdAt DESC');
        $stmt->execute(['userId' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateOrderStatus(int $id, string $status): bool
    {
        $stmt = $this->connection->prepare('UPDATE orders SET status = :status WHERE id = :id');
        $stmt->execute(['status' => $status, 'id' => $id]);
        return $stmt->rowCount() > 0;
    }

    public function deleteOrder(int $id): bool
    {
        try {
            $this->connection->beginTransaction();

            // Delete order items first
            $deleteItemsStmt = $this->connection->prepare('DELETE FROM orderItems WHERE orderId = :id');
            $deleteItemsStmt->execute(['id' => $id]);

            // Delete order
            $deleteOrderStmt = $this->connection->prepare('DELETE FROM orders WHERE id = :id');
            $deleteOrderStmt->execute(['id' => $id]);

            $this->connection->commit();
            return true;
        } catch (PDOException $e) {
            $this->connection->rollBack();
            return false;
        }
    }
}
