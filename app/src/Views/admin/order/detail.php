<h1>Order #<?= $order['id'] ?></h1>
<p><strong>Customer:</strong> <?= htmlspecialchars($order['customerName']) ?> (<?= htmlspecialchars($order['customerEmail']) ?>)</p>
<p><strong>Total:</strong> €<?= number_format($order['totalCents'] / 100, 2) ?></p>
<p><strong>Date:</strong> <?= $order['createdAt'] ?></p>

<div class="card mb-4" style="max-width: 400px;">
    <div class="card-body">
        <h5 class="card-title">Order Status</h5>
        <form method="POST" action="/admin/orders/<?= (int)$order['id'] ?>/status">
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select id="status" name="status" class="form-select" required>
                    <option value="pending" <?= ($order['status'] ?? 'pending') === 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="shipped" <?= ($order['status'] ?? 'pending') === 'shipped' ? 'selected' : '' ?>>Shipped</option>
                    <option value="delivered" <?= ($order['status'] ?? 'pending') === 'delivered' ? 'selected' : '' ?>>Delivered</option>
                    <option value="cancelled" <?= ($order['status'] ?? 'pending') === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update Status</button>
        </form>
    </div>
</div>

<h2>Items</h2>
<table class="table">
    <thead>
        <tr>
            <th>Minifigure</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Line Total</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($order['items'] as $item): ?>
        <tr>
            <td><?= htmlspecialchars($item['name']) ?></td>
            <td><?= $item['quantity'] ?></td>
            <td>€<?= number_format($item['priceCents'] / 100, 2) ?></td>
            <td>€<?= number_format($item['lineTotalCents'] / 100, 2) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div class="mt-4">
    <a href="/admin/orders" class="btn btn-secondary">Back to Orders</a>
    <form method="POST" action="/admin/orders/<?= (int)$order['id'] ?>/delete" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this order?');">
        <button type="submit" class="btn btn-danger">Delete Order</button>
    </form>
</div>