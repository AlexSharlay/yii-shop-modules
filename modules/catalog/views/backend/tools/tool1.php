<?php
use yii\helpers\Html;

?>
<div class="row">
    <div class="col-lg-6">
        <p><?=$mes;?></p>
        <p>Step 1</p>
        <div>
            <?
            echo Html::beginForm('/backend/catalog/tools/tool1/');
            echo Html::input('text','alias',null,['placeholder'=>'alias']);
            echo Html::submitButton('Скачать', ['class' => 'btn btn-primary']);
            echo Html::endForm();
            ?>
        </div>
    </div>
    <div class="col-lg-6">

    </div>
</div>
