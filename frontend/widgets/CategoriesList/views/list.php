<?php

use yii\helpers\Url;

?>

<?php if ($categories) {?>
    <div id="categories-list" class="content-mid">

        <div class="mid-popular">

            <?php foreach ($categories as $category) {?>
                <div class="col-md-3 item-grid1 categories-grid">
                    <div class=" mid-pop">
                        <div class="pro-img">

                            <?php
                            $mainImage = $category->getImage();
                            ?>

                            <img src="<?=$mainImage?>" class="img-responsive main-image" alt="">
                            <div class="zoom-icon ">
                                <a href="<?=Url::to(['category/index', 'slug' => $category->slug])?>"><i class="glyphicon glyphicon-menu-right icon"></i></a>
                            </div>

                            <div class="item-header"><?=$category->name; ?></div>
                        </div>

                    </div>
                </div>
            <?php } ?>
            <div class="clearfix"></div>
        </div>
    </div>
<?php } ?>
