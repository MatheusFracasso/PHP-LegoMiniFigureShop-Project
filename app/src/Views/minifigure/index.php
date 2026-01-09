

<?php foreach ($minifigures as $fig): ?>
<div class="card p-3 m-3 shadow">
    
    <img src="<?= $fig->image_url ?? '/assets/no-image.png' ?>" 
         alt="<?= htmlspecialchars($fig->name) ?>" 
         style="max-width:150px">

    <h3><?= htmlspecialchars($fig->name) ?></h3>
    <p><?= htmlspecialchars($fig->category_name) ?></p>
    <p><?= $fig->priceEuro() ?></p>

    <a href="/minifigures/<?= $fig->id ?>" class="btn btn-primary">View details</a>
    <form method="POST" action="/cart/add/<?= (int)$fig->id ?>">
    <button type="submit">Add to cart</button>
</form>
</div>
<?php endforeach; ?>