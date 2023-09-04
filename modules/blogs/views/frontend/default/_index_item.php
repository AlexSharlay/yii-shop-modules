<?php

/**
 * Blog list item view.
 *
 * @var \yii\web\View $this View
 * @var \common\modules\blogs\models\frontend\Blog $model Model
 */

use common\modules\blogs\Module;
use yii\helpers\Html;

?>
<li class="anons_list_item">
    <div class="col-xs-4">
        <?php if ($model->preview_url) : ?>
            <?= Html::a(
                Html::img(
                    $model->urlAttribute('preview_url'),
//                    ['class' => 'blog-img', 'width' => '100%', 'alt' => $model->title]
//                    ['class' => 'blog-img', 'max-width' => '440px', 'max-height' => '350px', 'alt' => $model->title]
                    ['class' => 'blog-img blog-news-img', 'alt' => $model->title]
                ),
                ['/' . $alias . '/' . $model->alias]
            ) ?>
        <?php endif; ?>
    </div>

    <div class="col-xs-8 anons_list_content">
        <h2 class="anons_list_header">
            <?= Html::a($model->title, ['/' . $alias . '/' . $model->alias]) ?>
        </h2>

<!--        <div class="entry-meta">-->
<!--            <span><i class="icon-calendar"></i> --><?//= $model->created ?><!--</span>-->
<!--            <span><i class="icon-eye"></i> --><?// //= $model->views ?><!--</span>-->
<!--        </div>-->

        <br/>

        <div style="color: #000;">
            <?= $model->snippet ?>
        </div>

        <br/>
        <?= Html::a(
            'Подробнее',
            ['/' . $alias . '/' . $model->alias]
        ) ?>
    </div>

</li>