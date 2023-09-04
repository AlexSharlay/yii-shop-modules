<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Отзывы';
$this->params['breadcrumbs'][] = $this->title;

$bundle = \frontend\themes\shop\pageAssets\page\review::register($this);

?>

<div class="row review-page">
    <div class="review-index">

        <h3><?= Html::encode($this->title) ?></h3>

        <div id="success" class="alert alert-success fade in" style="display: none">
            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
            <strong>Отзыв оставлен!</strong> Отзыв будет опубликован после проверки администратором.
        </div>
        <div id="warning" class="alert alert-warning fade in" style="display: none">
            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
            <strong>Отзыв не оставлен!</strong> Заполните пожалуйста все поля.
        </div>

        <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-12">
                <div class="review-page_list">
                    <?= ListView::widget([
                        'dataProvider' => $dataProvider,
                        'itemOptions' => ['class' => 'item'],
                        'itemView' => function ($model, $key, $index, $widget) {
                            $city = ($model->city) ? ', '.$model->city : '';
                            if ($model->mark == 0) {
                                $mark = '<i class="glyphicon glyphicon-star-empty"></i><i class="glyphicon glyphicon-star-empty"></i><i class="glyphicon glyphicon-star-empty"></i><i class="glyphicon glyphicon-star-empty"></i><i class="glyphicon glyphicon-star-empty"></i>';
                            } else if ($model->mark == 1) {
                                $mark = '<i class="glyphicon glyphicon-star"></i><i class="glyphicon glyphicon-star-empty"></i><i class="glyphicon glyphicon-star-empty"></i><i class="glyphicon glyphicon-star-empty"></i><i class="glyphicon glyphicon-star-empty"></i>';
                            } else if ($model->mark == 2) {
                                $mark = '<i class="glyphicon glyphicon-star"></i><i class="glyphicon glyphicon-star"></i><i class="glyphicon glyphicon-star-empty"></i><i class="glyphicon glyphicon-star-empty"></i><i class="glyphicon glyphicon-star-empty"></i>';
                            } else if ($model->mark == 3) {
                                $mark = '<i class="glyphicon glyphicon-star"></i><i class="glyphicon glyphicon-star"></i><i class="glyphicon glyphicon-star"></i><i class="glyphicon glyphicon-star-empty"></i><i class="glyphicon glyphicon-star-empty"></i>';
                            } else if ($model->mark == 4) {
                                $mark = '<i class="glyphicon glyphicon-star"></i><i class="glyphicon glyphicon-star"></i><i class="glyphicon glyphicon-star"></i><i class="glyphicon glyphicon-star"></i><i class="glyphicon glyphicon-star-empty"></i>';
                            } else if ($model->mark == 5) {
                                $mark = '<i class="glyphicon glyphicon-star"></i><i class="glyphicon glyphicon-star"></i><i class="glyphicon glyphicon-star"></i><i class="glyphicon glyphicon-star"></i><i class="glyphicon glyphicon-star"></i>';
                            }
                            return
                                '<div class="panel panel-flat border-left-info">
                    <div class="panel-heading">
                        <h6 class="panel-title">'.$mark.' '.$model->name.$city.'</h6>
                        <div class="heading-elements">'.$model->date.'</div>
                    </div>
                    <div class="panel-body">
                       '.$model->desc.'
                    </div>
                </div>';
                        },
                    ]) ?>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12">
                <div class="mt-20 visible-sm">
                    <hr/>
                </div>
                <div class="review-form">
                    <form>
                        <input type="hidden" name="mark">
                        <div class="star">
                            <i class="glyphicon glyphicon-star-empty"></i>
                            <i class="glyphicon glyphicon-star-empty"></i>
                            <i class="glyphicon glyphicon-star-empty"></i>
                            <i class="glyphicon glyphicon-star-empty"></i>
                            <i class="glyphicon glyphicon-star-empty"></i>
                        </div>
                        <div class="form-group field-review-name">
                            <label class="control-label" for="review-name">Имя</label>
                            <input type="text" id="review-name" class="form-control" name="name" maxlength="255">
                        </div>
                        <div class="form-group field-review-city">
                            <label class="control-label" for="review-city">Город</label>
                            <input type="text" id="review-city" class="form-control" name="city" maxlength="255">
                        </div>
                        <div class="form-group field-review-desc">
                            <label class="control-label" for="review-desc">Отзыв</label>
                            <textarea id="review-desc" class="form-control" name="desc" maxlength="255" rows="5"></textarea>
                        </div>
                        <div class="form-group">
                            <div class="btn btn-default">Оставить отзыв</div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>