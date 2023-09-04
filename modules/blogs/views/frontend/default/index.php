<?php

/**
 * Blog list page view.
 *
 * @var \yii\web\View $this View
 * @var \yii\data\ActiveDataProvider $dataProvider DataProvider
 */

use common\modules\blogs\Module;
use yii\widgets\ListView;

\common\modules\mods\mods_seo\components\seo::setMeta([
    'seo_title' => $model['seo_title'],
    'seo_keyword' => $model['seo_keyword'],
    'seo_desc' => $model['seo_desc']
]);
//$this->params['breadcrumbs'][] = $this->title;
?>
<section>

    <div class="main_content">
        <div class="warp">
            <h1 class="rubric akcent alpha"><i></i><?= $model->title ?></h1>
            <br/>
            <?= ListView::widget(
                [
                    'dataProvider' => $dataProvider,
                    'layout' => "{items}\n{pager}",
                    'itemView' => '_index_item',
                    'options' => [
                        'class' => 'blog'
                    ],
                    'itemOptions' => [
                        'class' => 'row',
                        'tag' => 'div'
                    ],
                    'viewParams' => [
                        'alias' => $model['alias']
                    ]
                ]
            ); ?>
        </div>
    </div>

</section>