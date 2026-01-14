
<div class="container py-5">
    <div class="row">
        <div class="col-12 text-center mb-5">
            <h1 class="display-4 fw-bold text-primary">Lego Minifigure Shop</h1>
            <p class="lead text-muted">Discover and collect amazing Lego minifigures from various themes</p>
        </div>
    </div>

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
<?php if (empty($minifigures)): ?>
    <div class="col-12">
        <div class="alert alert-info text-center">
            <h4>No minifigures available</h4>
            <p>Check back soon for new arrivals!</p>
        </div>
    </div>
<?php else: ?>
<?php foreach ($minifigures as $fig): ?>
<div class="col-12 col-sm-6 col-lg-4 col-xl-3">
    <div class="card h-100 shadow-sm border-0">
        <?php $imagePath = getImagePath($fig->name); ?>
        <img src="<?= $imagePath ?>"
             alt="<?= htmlspecialchars($fig->name) ?>"
             class="card-img-top"
             style="object-fit: cover; height: 200px;">

        <div class="card-body d-flex flex-column text-center">
            <h5 class="card-title"><?= htmlspecialchars($fig->name) ?></h5>
            <p class="card-text text-muted small"><?= htmlspecialchars($fig->categoryName ?? $fig->category) ?></p>
            <p class="card-text fw-bold text-primary h5 mb-3"><?= $fig->priceEuro() ?></p>

            <div class="mt-auto">
                <a href="/minifigures/<?= $fig->id ?>" class="btn btn-primary btn-sm me-2">View Details</a>
                <form method="POST" action="/cart/add/<?= (int)$fig->id ?>" style="display:inline;">
                    <button type="submit" class="btn btn-success btn-sm">Add to Cart</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>
<?php endif; ?>
    </div>
</div>