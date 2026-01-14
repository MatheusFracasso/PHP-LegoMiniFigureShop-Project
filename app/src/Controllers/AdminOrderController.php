<?php
namespace App\Controllers;

use App\Helpers\Authorization;
use App\Repositories\OrderRepository;

class AdminOrderController
{
    private OrderRepository $repo;

    public function __construct()
    {
        $this->repo = new OrderRepository();
    }

    public function index(array $parameters = []): void
    {
        Authorization::requireAdmin();

        $pageTitle = "Admin - Orders";
        $orders = $this->repo->getAllOrders();

        $contentView = __DIR__ . '/../Views/admin/order/index.php';
        require __DIR__ . '/../Views/layout/main.php';
    }

    public function detail(array $parameters = []): void
    {
        Authorization::requireAdmin();

        $id = isset($parameters['id']) ? (int)$parameters['id'] : 0;
        $order = $this->repo->getOrderById($id);

        if ($order === null) {
            http_response_code(404);
            echo 'Order not found';
            return;
        }

        $pageTitle = 'Admin - Order #' . $id;

        $contentView = __DIR__ . '/../Views/admin/order/detail.php';
        require __DIR__ . '/../Views/layout/main.php';
    }
}