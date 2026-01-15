<?php
function euroFromCents(int $cents): string {
    return '€' . number_format($cents / 100, 2, '.', '');
}
?>

<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">My Orders</h1>

            <?php if (empty($orders)): ?>
                <div class="alert alert-info">
                    <p>You haven't placed any orders yet.</p>
                    <a href="/minifigures" class="btn btn-primary">Continue Shopping</a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Date</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td>#<?= (int)$order['id'] ?></td>
                                    <td><?= htmlspecialchars($order['createdAt']) ?></td>
                                    <td><?= euroFromCents((int)$order['totalCents']) ?></td>
                                    <td>
                                        <a href="/order/<?= (int)$order['id'] ?>" class="btn btn-sm btn-primary">View Details</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
