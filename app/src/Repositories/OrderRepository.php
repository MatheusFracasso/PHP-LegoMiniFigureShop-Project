<?php

namespace App\Repositories;

use App\Config;
use PDO;
use PDOException;

class OrderRepository
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
    public function createOrder(string $customerName, string $customerEmail, int $totalCents, array $items): int
    {
        try {
            $this->connection->beginTransaction();

            // Insert into orders
            $stmt = $this->connection->prepare('
                INSERT INTO orders (customerName, customerEmail, totalCents)
                VALUES (:customerName, :customerEmail, :totalCents)
            ');
            $stmt->execute([
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
}
