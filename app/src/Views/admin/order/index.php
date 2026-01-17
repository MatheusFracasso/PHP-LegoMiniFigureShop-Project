<h1>Admin - Orders</h1>
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Customer Name</th>
            <th>Email</th>
            <th>Total</th>
            <th>Status</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($orders as $order) : ?>
        <tr>
            <td><?= $order['id'] ?></td>
            <td><?= htmlspecialchars($order['customerName']) ?></td>
            <td><?= htmlspecialchars($order['customerEmail']) ?></td>
            <td>€<?= number_format($order['totalCents'] / 100, 2) ?></td>
            <td>
                <?php
                    $status = $order['status'] ?? 'pending';
                    $badgeClass = 'warning';
                if ($status === 'paid') {
                    $badgeClass = 'success';
                } elseif ($status === 'cancelled') {
                    $badgeClass = 'danger';
                }
                ?>
                <span class="badge bg-<?= $badgeClass ?>">
                    <?= ucfirst($status) ?>
                </span>
            </td>
            <td><?= $order['createdAt'] ?></td>
            <td>
                <a href="/admin/orders/<?= $order['id'] ?>" class="btn btn-sm btn-primary">View Details</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
