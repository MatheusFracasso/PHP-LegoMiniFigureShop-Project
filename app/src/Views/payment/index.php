<?php
function euroFromCents(int $cents): string {
    return '€' . number_format($cents / 100, 2, '.', '');
}
?>

<h1>Payment</h1>

<?php if (!empty($error)): ?>
    <p style="color: red; background-color: #ffe6e6; padding: 10px; border-radius: 4px; margin: 20px 0;">
        ❌ <?= htmlspecialchars($error) ?>
    </p>
<?php endif; ?>

<div style="background-color: #f9f9f9; padding: 15px; border-radius: 4px; margin: 20px 0;">
    <p><strong>Order Amount:</strong> <?= $totalEuros ?? '€0.00' ?></p>
</div>

<form method="POST" action="/payment/<?= $orderId ?? '' ?>">
    <label>Card Number</label><br>
    <input type="text" name="cardNumber" placeholder="1234 5678 9012 3456" maxlength="19" required><br><br>

    <label>Expiry (MM/YY)</label><br>
    <input type="text" name="expiry" placeholder="12/25" maxlength="5" required><br><br>

    <label>CVV</label><br>
    <input type="text" name="cvv" placeholder="123" maxlength="3" required><br><br>

    <button type="submit" style="padding: 10px 20px; font-size: 16px;">Pay <?= $totalEuros ?? '€0.00' ?></button>
</form>

<p style="margin-top: 20px; color: #666; font-size: 0.9em;">
    💡 For testing: Enter any card number, expiry, and CVV. Payment has a 90% success rate.
</p>

<p style="margin-top: 10px;">
    <a href="/order/<?= $orderId ?? '' ?>">← Back to Order</a>
</p>
