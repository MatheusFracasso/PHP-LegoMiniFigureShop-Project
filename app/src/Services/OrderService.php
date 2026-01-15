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

    /**
     * Create an order from the current session cart.
     * Validates input, rebuilds cart, creates order, clears cart.
     */
    public function createOrderFromCart(int $userId, string $customerName, string $customerEmail): int|array
    {
        // Validate input
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

        // Rebuild cart items & totals (never trust only POST)
        // This ensures prices haven't been tampered with
        $items = [];
        $totalCents = 0;

        foreach ($cart as $id => $qty) {
            $id = (int)$id;
            $qty = (int)$qty;

            $minifigure = $this->minifigureService->getById($id);
            if ($minifigure !== null) {
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

        // Validate that we have items (in case some items were deleted from DB)
        if (empty($items)) {
            return ['error' => 'Some items in your cart are no longer available.'];
        }

        // Create the order
        $orderId = $this->orderRepository->createOrder(
            $customerName,
            $customerEmail,
            $totalCents,
            $items,
            $userId
        );

        // Clear cart
        $this->cartService->clearCart();

        return $orderId;
    }
}
