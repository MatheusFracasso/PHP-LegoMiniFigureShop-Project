<article class="row g-4 align-items-start">
  <div class="col-12 col-md-6">
    <img src="<?= htmlspecialchars($minifigure->imageUrl) ?>" class="img-fluid rounded" alt="<?= htmlspecialchars($minifigure->name) ?>">
  </div>
  <div class="col-12 col-md-6">
    <h1 class="mb-2"><?= htmlspecialchars($minifigure->name) ?></h1>
    <p class="text-muted"><?= htmlspecialchars($minifigure->category) ?></p>
    <p class="lead"><?= htmlspecialchars($minifigure->description) ?></p>
    <p class="h4 mt-3"><?= htmlspecialchars($minifigure->priceEuro()) ?></p>

    <form method="post" action="/cart/add">
      <input type="hidden" name="id" value="<?= (int)$minifigure->id ?>">
      <button class="btn btn-success mt-3" type="submit">Adicionar ao carrinho</button>
    </form>
  </div>
</article>
