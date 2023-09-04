<?php
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

?>
<style>
    .log span {
        margin-bottom: 1px;
        text-align: left;
    }
</style>

<div class="row">
    <div class="col-lg-12 log">
        <?
        if (count($results) > 0) {
            foreach ($results as $result) {
                $type = $result['type'];
                $msg = $result['msg'];
                if ($type == 'error') {
                    foreach ($msg['errors'] as $e) {
                        foreach ($e as $m) {
                            echo '<span class="label label-flat label-block border-danger text-danger-600">Ошибка: ' . $m . '</span>';
                        }
                    }
                    /*
                    echo '<pre>';
                    print_r($msg);
                    echo '</pre>';
                    */
                } else {
                    echo '<span class="label label-flat label-block border-' . $type . ' text-' . $type . '-600">' . $msg . '</span>';
                }
            }
            echo '<br/>';
        }
        ?>

        <a href="/export/cron.php/" class="btn btn-primary">Сформировать XML</a>

        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

        <!--div class="form-group">
            <select class="bootstrap-select" data-width="100%">
                <option>-- Режим импорта --</option>
                <option value="1">Добавлять новые, изменять существующие</option>
                <option value="2">Добавлять новые, не изменять существующие</option>
                <option value="3">Изменять существующие, не добавлять новые</option>
            </select>
        </div-->

        <?= $form->field($model, 'import_type')->dropdownList([
            '1' => 'Добавлять новые, изменять существующие',
            '2' => 'Добавлять новые, не изменять существующие',
            '3' => 'Изменять существующие, не добавлять новые',
        ], [
            'prompt' => 'Select Category'
        ]);
        ?>

        <?= $form->field($model, 'xlsxFile')->fileInput(['class' => 'form-control'])->label('Выберите .xlsx файл для импорта/обновления товаров.'); ?>
        <?= Html::submitButton('<i class="icon-file-download position-left"></i>Импортировать', ['class' => 'btn btn-default']) ?>
        <?php ActiveForm::end() ?>
    </div>
</div>

<hr/>


<div class="row">
    <div class="col-lg-3">
        <p>Порядок полей в файле импорта:</p>
        <ul>
            <li>Тип товара</li>
            <li>Категория, id</li>
            <li>Бренд</li>
            <li>Префикс</li>
            <li>Имя</li>
            <li>Имя модели</li>
            <li>Артикул</li>
            <li>Код 1С</li>
            <li>Количество на складе</li>
            <li>Публикация</li>
            <li>Цена (=Цена 1С)</li>
            <li>Наименование как модели</li>
            <li>Гарантия, мес</li>
            <li>Срок годности, мес</li>
            <li>Производитель</li>
            <li>Импортер</li>
            <li>Сервисный центр</li>
            <li>onliner.by</li>
            <li>1k.by</li>
            <li>shop.by</li>
            <li>kypi.tut.by</li>
            <li>unishop.by</li>
        </ul>
    </div>
    <div class="col-lg-3">
        <p>При добавлении:</p>
        <ul>
            <li>Тип товара</li>
            <li>Категория, id</li>
            <li>Бренд</li>
            <li>Префикс</li>
            <li>Имя</li>
            <li>Имя модели</li>
            <li>Артикул</li>
            <li>I +- Код 1С</li>
            <li>J +- Количество на складе</li>
            <li>K +- Публикация</li>
            <li>L +- Цена (=Цена 1С) (Например: 59,06 (руб.коп.))</li>
            <li>Наименование как модели</li>
            <li>Гарантия, мес</li>
            <li>Срок годности, мес</li>
            <li>Производитель</li>
            <li>Импортер</li>
            <li>Сервисный центр</li>
            <li>onliner.by</li>
            <li>1k.by</li>
            <li>shop.by</li>
            <li>kypi.tut.by</li>
            <li>unishop.by</li>
            <li>W +- Статус товара (3 - экспозиция)</li>
            <li>X +- Акция</li>
            <li>Y +- Новинка</li>
            <li>Z +- Халва</li>
            <li>AA+- Старая цена (Например: 59,06 (руб.коп.))</li>
            <li>AB+- alias</li>
            <li>AC+- Заводской артикул</li>
        </ul>
    </div>
    <div class="col-lg-3">
        <p>При обновлении:</p>
        <ul>
            <li> +- Тип товара</li>
            <li>B +- Категория, id</li>
            <li>C +- Бренд</li>
            <li>D +- Префикс</li>
            <li>E +- Имя</li>
            <li>F +- Имя модели</li>
            <li>G +- Сортировка</li>
            <li>H +- Артикул</li>
            <li>I + Код 1С</li>
            <li>J +- Количество на складе</li>
            <li>K +- Публикация</li>
            <li>L +- Цена (Например: 59,06 (руб.коп.))</li>
            <li>+- Наименование как модели</li>
            <li>M +- Гарантия, мес</li>
            <li>N +- Срок годности, мес</li>
            <li>O +- Страна производства</li>
            <li>P +- Импортер</li>
            <li>Q +- Сервисный центр</li>
            <li>R +- onliner.by</li>
            <li>S +- 1k.by</li>
            <li>T +- shop.by</li>
            <li>U +- market.yandex.by</li>
            <li>V +- unishop.by</li>
            <li>W +- Статус товара (3 - экспозиция)</li>
            <li>X +- Акция</li>
            <li>Y +- Новинка</li>
            <li>Z +- Халва</li>
            <li>AA+- Старая цена (Например: 59,06 (руб.коп.))</li>
            <li>AB+- alias</li>
            <li>AC+- Заводской артикул</li>
        </ul>
    </div>
    <div class="col-lg-3">
        <p>Если при обновлении ячейка не пуста, то она обновляется</p>
    </div>
</div>
