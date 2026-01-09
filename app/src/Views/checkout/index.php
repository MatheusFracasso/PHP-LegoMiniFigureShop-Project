<?php
function euroFromCents(int $cents): string {
    return '€' . number_format($cents / 100, 2, '.', '');
}
?>

<h1>Checkout</h1>

<?php if (!empty($error)): ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<h3>Order Summary</h3>
<ul>
<?php foreach ($cartItems as $item): ?>
    <li>
        <?= htmlspecialchars($item['minifigure']->name) ?>
        x <?= (int)$item['quantity'] ?>
        = <?= euroFromCents((int)$item['lineTotalCents']) ?>
    </li>
<?php endforeach; ?>
</ul>

<p><strong>Total: <?= euroFromCents($totalCents) ?></strong></p>

<form method="POST" action="/checkout">
    <label>Name</label><br>
    <input type="text" name="customerName" required><br><br>

    <label>Email</label><br>
    <input type="email" name="customerEmail" required><br><br>

    <button type="submit">Place Order</button>
</form>
