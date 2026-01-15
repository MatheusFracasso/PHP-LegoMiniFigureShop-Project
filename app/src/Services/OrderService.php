<?php

namespace App\Services;

use App\Repositories\OrderRepository;

class OrderService implements IOrderService
{
    private CartService $cartService;
    private MinifigureService $minifigureService;
    private OrderRepository $orderRepository;

    public function __construct()
    {
        $this->cartService = new CartService();
        $this->minifigureService = new MinifigureService();
        $this->orderRepository = new OrderRepository();
    }

    public function createOrderFromCart(?int $userId, string $customerName, string $customerEmail): int|array
    {
        $customerName = trim($customerName);
        $customerEmail = trim($customerEmail);

        if ($customerName === '' || $customerEmail === '') {
            return ['error' => 'Please fill in name and email.'];
        }

        // Get current cart from session
        $cart = $_SESSION['cart'] ?? [];
        if (empty($cart)) {
            return ['error' => 'Your cart is empty.'];
        }

        // Rebuild cart with current prices to prevent tampering
        $items = [];
        $totalCents = 0;

        foreach ($cart as $id => $qty) {
            $minifigure = $this->minifigureService->getById((int)$id);
            if ($minifigure !== null) {
                $qty = (int)$qty;
                $lineTotal = $minifigure->priceCents * $qty;
                $totalCents += $lineTotal;

                $items[] = [
                    'minifigureId' => $minifigure->id,
                    'quantity' => $qty,
                    'priceCents' => $minifigure->priceCents,
                    'lineTotalCents' => $lineTotal
                ];
            }
        }

        if (empty($items)) {
            return ['error' => 'Some items in your cart are no longer available.'];
        }

        $orderId = $this->orderRepository->createOrder(
            $customerName,
            $customerEmail,
            $totalCents,
            $items,
            $userId
        );

        $this->cartService->clearCart();

        return $orderId;
    }
}
