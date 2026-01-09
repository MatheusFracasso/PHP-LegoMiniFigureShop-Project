<?php
// Variables available:
// $cartItems (array), $totalCents (int)

function euroFromCents(int $cents): string
{
    return '€' . number_format($cents / 100, 2, '.', '');
}
?>

<h1>Your Cart</h1>

<?php if (empty($cartItems)): ?>
    <p>Your cart is empty.</p>
    <p><a href="/minifigures">Back to shop</a></p>
<?php else: ?>

    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>Minifigure</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Line total</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($cartItems as $item): ?>
            <?php $fig = $item['minifigure']; ?>
            <tr>
                <td><?= htmlspecialchars($fig->name) ?></td>
                <td><?= htmlspecialchars($fig->priceEuro()) ?></td>
                <td>
                    <form method="POST" action="/cart/update/<?= (int)$fig->id ?>">
                        <input type="number" name="quantity" value="<?= (int)$item['quantity'] ?>" min="0">
                        <button type="submit">Update</button>
                    </form>
                </td>
                <td><?= euroFromCents((int)$item['lineTotalCents']) ?></td>
                <td>
                    <form method="POST" action="/cart/remove/<?= (int)$fig->id ?>">
                        <button type="submit">Remove</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <h3>Total: <?= euroFromCents($totalCents) ?></h3>

    <p><a href="/minifigures">Continue shopping</a></p>

<?php endif; ?>
