<?php

require __DIR__. '/../partials/head.php';
require __DIR__ . '/../partials/header.php';

if (isset($contentView) && is_file($contentView)){
    require $contentView;
} else {
    echo '<div class="alert alert-danger">View not found.</div>';
}

require __DIR__ . '/../partials/footer.php';
?>