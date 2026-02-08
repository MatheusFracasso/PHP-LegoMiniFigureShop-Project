<h1>Admin - Invitations History</h1>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success">
        <?= htmlspecialchars($_SESSION['success']) ?>
        <?php unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<div class="mb-3">
    <a href="/admin/invitations/create" class="btn btn-primary">Send New Invitation</a>
</div>

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Email</th>
            <th>Invited By</th>
            <th>Status</th>
            <th>Created At</th>
            <th>Accepted At</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($invitations)): ?>
        <tr>
            <td colspan="6" class="text-center">No invitations found</td>
        </tr>
        <?php else: ?>
            <?php foreach ($invitations as $invitation) : ?>
            <tr>
                <td><?= htmlspecialchars($invitation['id']) ?></td>
                <td><?= htmlspecialchars($invitation['email']) ?></td>
                <td><?= htmlspecialchars($invitation['invitedByName']) ?> (<?= htmlspecialchars($invitation['invitedByEmail']) ?>)</td>
                <td>
                    <?php
                        $status = $invitation['status'] ?? 'pending';
                        $badgeClass = 'warning';
                        if ($status === 'accepted') {
                            $badgeClass = 'success';
                        } elseif ($status === 'rejected') {
                            $badgeClass = 'danger';
                        }
                    ?>
                    <span class="badge bg-<?= $badgeClass ?>">
                        <?= ucfirst($status) ?>
                    </span>
                </td>
                <td><?= $invitation['createdAt'] ?></td>
                <td><?= $invitation['acceptedAt'] ?? 'N/A' ?></td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>
