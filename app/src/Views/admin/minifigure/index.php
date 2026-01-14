<h1>Admin - Minifigures</h1>
<a href="/admin/minifigures/create" class="btn btn-primary">Create New Minifigure</a>
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Price</th>
            <th>Category</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($minifigures as $fig): ?>
        <tr>
            <td><?= $fig->id ?></td>
            <td><?= htmlspecialchars($fig->name) ?></td>
            <td><?= $fig->priceEuro() ?></td>
            <td><?= htmlspecialchars($fig->category) ?></td>
            <td>
                <a href="/admin/minifigures/edit/<?= $fig->id ?>" class="btn btn-sm btn-warning">Edit</a>
                <form method="POST" action="/admin/minifigures/delete/<?= $fig->id ?>" style="display:inline;">
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>