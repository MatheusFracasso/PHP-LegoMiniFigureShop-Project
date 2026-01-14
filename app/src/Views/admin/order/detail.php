<h1>Order #<?= $order['id'] ?></h1>
<p><strong>Customer:</strong> <?= htmlspecialchars($order['customerName']) ?> (<?= htmlspecialchars($order['customerEmail']) ?>)</p>
<p><strong>Total:</strong> €<?= number_format($order['totalCents'] / 100, 2) ?></p>
<p><strong>Date:</strong> <?= $order['createdAt'] ?></p>
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
<a href="/admin/orders" class="btn btn-secondary">Back to Orders</a>