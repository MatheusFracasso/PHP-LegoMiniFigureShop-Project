<?php

namespace App\Controllers;

use App\Helpers\Authorization;
use App\Repositories\OrderRepository;

class UserOrderController
{
    private OrderRepository $orderRepo;

    public function __construct()
    {
        $this->orderRepo = new OrderRepository();
    }

    public function myOrders(array $parameters = []): void
    {
        Authorization::requireLogin();

        $userId = $_SESSION['user']['id'];
        $orders = $this->orderRepo->getOrdersByUserId($userId);

        $pageTitle = "My Orders";
        $contentView = __DIR__ . '/../Views/account/orders.php';
        require __DIR__ . '/../Views/layout/main.php';
    }
}
