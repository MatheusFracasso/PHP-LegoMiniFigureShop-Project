<?php
function euroFromCents(int $cents): string {
    return '€' . number_format($cents / 100, 2, '.', '');
}
?>

<h1>Order Confirmed ✅</h1>

<p>Order #<?= (int)$order['id'] ?></p>
<p>Name: <?= htmlspecialchars($order['customerName']) ?></p>
<p>Email: <?= htmlspecialchars($order['customerEmail']) ?></p>
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

<p><a href="/minifigures">Back to shop</a></p>
