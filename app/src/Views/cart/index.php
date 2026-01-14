<?php
// Variables available:
// $cartItems (array), $totalCents (int)

function euroFromCents(int $cents): string
{
    return '€' . number_format($cents / 100, 2, '.', '');
}
?>

<div class="container py-5">
    <h1 class="mb-4">Your Cart</h1>

    <?php if (empty($cartItems)): ?>
        <div class="alert alert-info">
            <h4>Your cart is empty.</h4>
            <p><a href="/minifigures" class="btn btn-primary">Start Shopping</a></p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Minifigure</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Line Total</th>
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
                            <form method="POST" action="/cart/update/<?= (int)$fig->id ?>" class="d-inline">
                                <div class="input-group input-group-sm" style="width: 120px;">
                                    <input type="number" name="quantity" value="<?= (int)$item['quantity'] ?>" min="0" class="form-control">
                                    <button type="submit" class="btn btn-outline-secondary">Update</button>
                                </div>
                            </form>
                        </td>
                        <td><?= euroFromCents((int)$item['lineTotalCents']) ?></td>
                        <td>
                            <form method="POST" action="/cart/remove/<?= (int)$fig->id ?>" class="d-inline">
                                <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <h3>Total: <?= euroFromCents($totalCents) ?></h3>
            </div>
            <div class="col-md-6 text-end">
                <a href="/checkout" class="btn btn-success btn-lg">Go to Checkout</a>
            </div>
        </div>

        <div class="mt-3">
            <a href="/minifigures" class="btn btn-secondary">Continue Shopping</a>
        </div>
    <?php endif; ?>
</div>
