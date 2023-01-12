<?php if (!empty($images)) : ?>

<div class="gallery">

    <?php foreach ($images as $image) : ?>

    <a href="<?= $image['src']; ?>">
        <img src="<?= $image['src']; ?>"  class="lightboxed" rel="group1">
    </a>

    <?php endforeach; ?>

</div>

<?php endif; ?>
