<h1 class="mb-3"><?= htmlspecialchars($message ?? 'Welcome!') ?></h1>
<p class="lead">Start exploring LEGO minifigures using the Shop link.</p>

<!-- Example grid teaser (from grid section in lecture) -->
<div class="row g-3 mt-4">
  <div class="col-12 col-md-6 col-lg-4">
    <div class="card h-100">
      <img class="card-img-top" src="/assets/hero-minifig.jpg" alt="Featured minifigure">
      <div class="card-body">
        <h5 class="card-title">Featured Minifig</h5>
        <p class="card-text">Classic Space Explorer.</p>
        <a href="/minifigures/1" class="btn btn-primary">Details</a>
      </div>
    </div>
  </div>
</div>