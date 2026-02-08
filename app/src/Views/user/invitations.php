<h1>My Invitations</h1>

<p>Here you can see the history of invitations you've received.</p>

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Invited By</th>
            <th>Status</th>
            <th>Created At</th>
            <th>Accepted At</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($invitations)): ?>
        <tr>
            <td colspan="5" class="text-center">No invitations found</td>
        </tr>
        <?php else: ?>
            <?php foreach ($invitations as $invitation) : ?>
            <tr>
                <td><?= $invitation['id'] ?></td>
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
