

<div class="row g-4">
<?php
function getImagePath($name) {
    // Sanitize name: remove spaces, dashes, and capitalize words
    $sanitized = str_replace([' ', '-'], '', $name);
    // Special case for Jaheira
    if (strpos($name, 'Jaheira') !== false) {
        $sanitized = 'aheiraInspiredDruid'; // as per filename
    }
    return "/images/minifigures/{$sanitized}.png";
}
?>
<?php foreach ($minifigures as $fig): ?>
<div class="col-12 col-sm-6 col-lg-4">
    <div class="card h-100 shadow">
        <?php $imagePath = getImagePath($fig->name); ?>
        <img src="<?= $imagePath ?>" 
             alt="<?= htmlspecialchars($fig->name) ?>" 
             class="card-img-top"
             style="object-fit: cover; height: 250px;">

        <div class="card-body d-flex flex-column">
            <h5 class="card-title"><?= htmlspecialchars($fig->name) ?></h5>
            <p class="card-text text-muted"><?= htmlspecialchars($fig->categoryName ?? $fig->category) ?></p>
            <p class="card-text fw-bold"><?= $fig->priceEuro() ?></p>

            <div class="mt-auto">
                <a href="/minifigures/<?= $fig->id ?>" class="btn btn-primary btn-sm me-2">View details</a>
                <form method="POST" action="/cart/add/<?= (int)$fig->id ?>" style="display:inline;">
                    <button type="submit" class="btn btn-success btn-sm">Add to cart</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>
</div>