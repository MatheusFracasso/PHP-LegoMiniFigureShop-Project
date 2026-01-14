<h1>Admin - Users</h1>
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Email</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><?= $user['id'] ?></td>
            <td><?= htmlspecialchars($user['email']) ?></td>
            <td>
                <form method="POST" action="/admin/users/role/<?= $user['id'] ?>" style="display:inline;">
                    <select name="role" onchange="this.form.submit()">
                        <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>User</option>
                        <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                    </select>
                </form>
            </td>
            <td><!-- No delete for simplicity --></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>