<?php
use yii\helpers\Html;

$title = Html::encode($query);

$this->title = 'Поиск "' . $title . '"';
$this->params['breadcrumbs'] = [$this->title];
$this->registerMetaTag(['robots' => 'noindex,nofollow']);

$local_title = 'По запросу "' . $title . '"';
$total =  $result_count_items;
$local_title .= ' найдено';

if ($total >= $result_limit) {
    $local_title .= ' более ' . $result_limit . ' позиций, уточните запрос.';
} else {
    $local_title .= ': товаров - ' . ($result_count_items);
}
?>

<?php if (!empty($itemsFound)) : ?>

<h2 class="content__header-h2"><?= $local_title; ?></h2>

<div class="item-card catalog">

    <form action="/category/compare" method="post" id="comparison-form" target="_blank">
        <?= $itemsFound ?>
    </form>

</div>
<?php else : ?>
<h2 class="content__header-h1">Ничего не найдено
    <?php if (strlen($query) < 2) : ?>
        , слишком короткий запрос (меньше 2-х символов).
    <?php endif; ?>
</h2>
<?php endif; ?>