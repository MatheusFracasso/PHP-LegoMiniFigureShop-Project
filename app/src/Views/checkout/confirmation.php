<?php
function euroFromCents(int $cents): string {
    return '€' . number_format($cents / 100, 2, '.', '');
}
?>

<?php if ($isGuest ?? false): ?>
    <!-- Guest checkout confirmation -->
    <div style="background-color: #d4edda; padding: 20px; border-radius: 4px; margin-bottom: 20px;">
        <h1>✅ Order Confirmed</h1>
        <p>Thank you for your purchase!</p>
        <p>Order details have been sent to <strong><?= htmlspecialchars($order['customerEmail']) ?></strong></p>
        <p style="color: #666; margin-top: 15px;">You can check your email for order information, tracking, and receipt.</p>
    </div>

    <h3>Order Summary</h3>
    <p><strong>Order ID:</strong> #<?= (int)$order['id'] ?></p>
    <p><strong>Status:</strong> 
        <?php
            $statusBadge = '';
            if ($order['status'] === 'paid') {
                $statusBadge = '<span style="background-color: #28a745; color: white; padding: 4px 8px; border-radius: 4px;">Paid ✓</span>';
            } elseif ($order['status'] === 'pending') {
                $statusBadge = '<span style="background-color: #ffc107; color: black; padding: 4px 8px; border-radius: 4px;">Pending Payment</span>';
            } elseif ($order['status'] === 'cancelled') {
                $statusBadge = '<span style="background-color: #dc3545; color: white; padding: 4px 8px; border-radius: 4px;">Cancelled</span>';
            }
            echo $statusBadge;
        ?>
    </p>
    <p><strong>Total:</strong> <?= euroFromCents((int)$order['totalCents']) ?></p>

    <h3>Items</h3>
    <ul>
    <?php foreach ($order['items'] as $item): ?>
        <li>
            <?= htmlspecialchars($item['name']) ?>
            x <?= (int)$item['quantity'] ?>
            = <?= euroFromCents((int)$item['lineTotalCents']) ?>
        </li>
    <?php endforeach; ?>
    </ul>

    <p style="margin-top: 30px;"><a href="/minifigures">Continue Shopping</a></p>

<?php else: ?>
    <!-- Logged-in user confirmation -->
    <h1>Order Confirmed ✅</h1>

    <p>Order #<?= (int)$order['id'] ?></p>
    <p>Name: <?= htmlspecialchars($order['customerName']) ?></p>
    <p>Email: <?= htmlspecialchars($order['customerEmail']) ?></p>
    <p><strong>Status:</strong> 
        <?php
            $statusBadge = '';
            if ($order['status'] === 'paid') {
                $statusBadge = '<span style="background-color: #28a745; color: white; padding: 4px 8px; border-radius: 4px;">Paid ✓</span>';
            } elseif ($order['status'] === 'pending') {
                $statusBadge = '<span style="background-color: #ffc107; color: black; padding: 4px 8px; border-radius: 4px;">Pending Payment</span>';
            } elseif ($order['status'] === 'cancelled') {
                $statusBadge = '<span style="background-color: #dc3545; color: white; padding: 4px 8px; border-radius: 4px;">Cancelled</span>';
            }
            echo $statusBadge;
        ?>
    </p>
    <p>Total: <strong><?= euroFromCents((int)$order['totalCents']) ?></strong></p>

    <h3>Items</h3>
    <ul>
    <?php foreach ($order['items'] as $item): ?>
        <li>
            <?= htmlspecialchars($item['name']) ?>
            x <?= (int)$item['quantity'] ?>
            = <?= euroFromCents((int)$item['lineTotalCents']) ?>
        </li>
    <?php endforeach; ?>
    </ul>

    <p><a href="/minifigures">Back to shop</a> | <a href="/my-orders">View all orders</a></p>

<?php endif; ?>
