<?php if (!empty($images)) : ?>

<div class="news_item_images">

    <div class="items">

    <?php foreach ($images as $image) : ?>

        <a href="<?= $image['src']; ?>" class="item lightboxed">
            <img src="<?= $image['src']; ?>"  class="lightboxed" rel="group1">
        </a>

    <?php endforeach; ?>

    </div>

</div>

<?php endif; ?>
