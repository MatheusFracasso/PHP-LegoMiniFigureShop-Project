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
    <?php if (isset($_SESSION['user'])): ?>
        <input type="text" name="customerName" value="<?= htmlspecialchars($customerName) ?>" readonly><br>
        <small style="color: #666;">Name from your account</small><br><br>
    <?php else: ?>
        <input type="text" name="customerName" value="<?= htmlspecialchars($customerName ?? '') ?>" required><br><br>
    <?php endif; ?>

    <label>Email</label><br>
    <?php if (isset($_SESSION['user'])): ?>
        <input type="email" name="customerEmail" value="<?= htmlspecialchars($customerEmail) ?>" readonly><br>
        <small style="color: #666;">Email from your account</small><br><br>
    <?php else: ?>
        <input type="email" name="customerEmail" value="<?= htmlspecialchars($customerEmail ?? '') ?>" required><br><br>
    <?php endif; ?>

    <button type="submit">Place Order</button>
</form>
