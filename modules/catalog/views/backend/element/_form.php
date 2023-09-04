<?php

use yii\bootstrap\Tabs;

/* @var $this yii\web\View */
/* @var $model common\modules\catalog\models\Element */
/* @var $form yii\widgets\ActiveForm */

echo Tabs::widget([
    'items' => [
        [
            'label' => 'Товар',
//            'active' => ($controller == 'product') ? true : false,
            'active' => true,
            'options' => ['id' => 'tab1'],
            'content' => $this->render('_tab1', [
                'model' => $model,
                'modelPhoto' => $modelPhoto,
                'publishedArray' => $publishedArray,
                'categoryArray' => $categoryArray,
                'manufacturerArray' => $manufacturerArray,
                'measurementArray' => $measurementArray,
                'photos' => $photos,
                'parent' => $parent,
            ]),
        ],
        [
            'label' => 'Характеристики',
            'active' => ($tab['property']) ? true : false,
            'options' => ['id' => 'tab2'],
            'content' => $this->render('_tab2', [
                'model' => $model
            ]),
        ],
        [
            'label' => 'Комплект',
            'active' => ($tab['complect']) ? true : false,
            'options' => ['id' => 'tab3'],
            'content' => $this->render('_tab3', [
                'complects' => $complects,
                'model' => $model
            ]),
        ],
        [
            'label' => 'Модели',
            'active' => ($tab['model']) ? true : false,
            'options' => ['id' => 'tab4'],
            'content' => $this->render('_tab4', [
                'models' => $models,
                'model' => $model
            ]),
        ],
        [
            'label' => 'Наборы',
            'active' => ($tab['kit']) ? true : false,
            'options' => ['id' => 'tab5'],
            'content' => $this->render('_tab5', [
                'model' => $model
            ]),
        ],
    ],
]);