<h1>Edit Minifigure</h1>
<form method="POST">
    <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($minifigure->name) ?>" required>
    </div>
    <div class="mb-3">
        <label for="priceCents" class="form-label">Price (cents)</label>
        <input type="number" class="form-control" id="priceCents" name="priceCents" value="<?= $minifigure->priceCents ?>" required>
    </div>
    <div class="mb-3">
        <label for="categoryId" class="form-label">Category ID</label>
        <input type="number" class="form-control" id="categoryId" name="categoryId" value="1" required> <!-- Assume 1, or get from DB -->
    </div>
    <div class="mb-3">
        <label for="imageUrl" class="form-label">Image URL</label>
        <input type="text" class="form-control" id="imageUrl" name="imageUrl" value="<?= htmlspecialchars($minifigure->imageUrl) ?>">
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" id="description" name="description"><?= htmlspecialchars($minifigure->description) ?></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
    <a href="/admin/minifigures" class="btn btn-secondary">Cancel</a>
</form>
