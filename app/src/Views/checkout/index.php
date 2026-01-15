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

<?php if ($isLoggedIn ?? false): ?>
    <div style="background-color: #e7f3ff; padding: 10px; border-radius: 4px; margin: 20px 0;">
        <p>✓ Signed in as <strong><?= htmlspecialchars($_SESSION['user']['email']) ?></strong></p>
    </div>
<?php endif; ?>

<form method="POST" action="/checkout">
    <label>Name</label><br>
    <?php if ($isLoggedIn ?? false): ?>
        <input type="text" name="customerName" value="<?= htmlspecialchars($customerName) ?>" readonly><br>
        <small style="color: #666;">From your account</small><br><br>
    <?php else: ?>
        <input type="text" name="customerName" value="<?= htmlspecialchars($customerName ?? '') ?>" required><br><br>
    <?php endif; ?>

    <label>Email</label><br>
    <?php if ($isLoggedIn ?? false): ?>
        <input type="email" name="customerEmail" value="<?= htmlspecialchars($customerEmail) ?>" readonly><br>
        <small style="color: #666;">From your account</small><br><br>
    <?php else: ?>
        <input type="email" name="customerEmail" value="<?= htmlspecialchars($customerEmail ?? '') ?>" required><br><br>
    <?php endif; ?>

    <button type="submit">Place Order</button>

    <?php if (!($isLoggedIn ?? false)): ?>
        <p style="margin-top: 15px; color: #666; font-size: 0.9em;">
            <a href="/login">Already have an account? Login here</a>
        </p>
    <?php endif; ?>
</form>
