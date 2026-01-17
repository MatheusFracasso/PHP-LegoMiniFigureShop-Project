
<div class="container py-5">
    <div class="row">
        <div class="col-12 text-center mb-5">
            <h1 class="display-4 fw-bold">⚔️ Minifigure Collection</h1>
            <p class="lead" style="color: #6b5947;">Discover legendary heroes and mystical characters for your quest</p>
        </div>
    </div>

    <!-- Search Input -->
    <div class="row mb-4">
        <div class="col-12 col-md-8 col-lg-6 mx-auto">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0">🔍</span>
                <input type="text" id="searchInput" class="form-control border-start-0" 
                       placeholder="Search by name or category..." 
                       onkeyup="filterMinifigures()">
            </div>
        </div>
    </div>

    <div class="row g-4" id="minifiguresContainer">
<?php
function getImagePath($name)
{
    // Sanitize name: remove spaces, dashes, and capitalize words
    $sanitized = str_replace([' ', '-'], '', $name);
    // Special case for Jaheira
    if (strpos($name, 'Jaheira') !== false) {
        $sanitized = 'JaheiraInspiredDruid'; // matches filename
    }
    return "/images/minifigures/{$sanitized}.png";
}
?>
<?php if (empty($minifigures)) : ?>
    <div class="col-12">
        <div class="alert alert-info text-center">
            <h4>No minifigures available</h4>
            <p>Check back soon for new arrivals!</p>
        </div>
    </div>
<?php else : ?>
    <?php foreach ($minifigures as $fig) : ?>
<div class="col-12 col-sm-6 col-lg-4 col-xl-3 minifigure-card" 
     data-name="<?= htmlspecialchars(strtolower($fig->name)) ?>" 
     data-category="<?= htmlspecialchars(strtolower($fig->categoryName ?? $fig->category)) ?>">
    <div class="card h-100 shadow-sm">
        <?php $imagePath = getImagePath($fig->name); ?>
        <div class="position-relative">
            <img src="<?= htmlspecialchars($imagePath) ?>"
                 alt="<?= htmlspecialchars($fig->name) ?>"
                 class="card-img-top"
                 style="object-fit: cover; height: 220px;">
            <span class="badge bg-secondary position-absolute top-0 end-0 m-2">
                <?= htmlspecialchars($fig->categoryName ?? $fig->category) ?>
            </span>
        </div>

        <div class="card-body d-flex flex-column">
            <h5 class="card-title text-center mb-2"><?= htmlspecialchars($fig->name) ?></h5>
            <p class="price-tag text-center mb-3"><?= htmlspecialchars($fig->priceEuro()) ?></p>

            <div class="mt-auto d-grid gap-2">
                <a href="/minifigures/<?= (int)$fig->id ?>" class="btn btn-outline-primary btn-sm">
                    👁️ View Details
                </a>
                <form method="POST" action="/cart/add/<?= (int)$fig->id ?>">
                    <button type="submit" class="btn btn-success btn-sm w-100">
                        🛍️ Add to Cart
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
    <?php endforeach; ?>
<?php endif; ?>
    </div>
</div>

<script>
let allMinifigures = [];

// Fetch minifigures from API on page load
window.onload = function() {
    fetch('/api/minifigures')
        .then(response => response.json())
        .then(data => {
            allMinifigures = data;
            // Initial render is already done by PHP, but we store the data for filtering
        })
        .catch(error => console.error('Error fetching minifigures:', error));
};

function filterMinifigures() {
    const query = document.getElementById('searchInput').value.toLowerCase();
    const container = document.getElementById('minifiguresContainer');

    // Clear existing cards
    container.innerHTML = '';

    // Filter minifigures
    const filtered = allMinifigures.filter(fig =>
        fig.name.toLowerCase().includes(query) ||
        fig.category.toLowerCase().includes(query)
    );

    // Render filtered cards
    if (filtered.length === 0) {
        container.innerHTML = '<div class="col-12"><div class="alert alert-info text-center"><h4>No minifigures match your search</h4><p>Try a different search term.</p></div></div>';
    } else {
        filtered.forEach(fig => {
            const cardHtml = `
                <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                    <div class="card h-100 shadow-sm border-0">
                        <img src="${fig.imageUrl}" alt="${fig.name}" class="card-img-top" style="object-fit: cover; height: 200px;">
                        <div class="card-body d-flex flex-column text-center">
                            <h5 class="card-title">${fig.name}</h5>
                            <p class="card-text text-muted small">${fig.category}</p>
                            <p class="card-text fw-bold text-primary h5 mb-3">${fig.priceEuro}</p>
                            <div class="mt-auto">
                                <a href="/minifigures/${fig.id}" class="btn btn-primary btn-sm me-2">View Details</a>
                                <form method="POST" action="/cart/add/${fig.id}" style="display:inline;">
                                    <button type="submit" class="btn btn-success btn-sm">Add to Cart</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', cardHtml);
        });
    }
}
</script>
