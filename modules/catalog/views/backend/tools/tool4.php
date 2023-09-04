<?php
use yii\helpers\Html;

?>
<div class="row">
    <div class="col-lg-6">
        <div style=" word-wrap: break-word;"><?=$mes;?></div>
        <p>Tool 4</p>
        <div>
            <?
            echo Html::beginForm('/backend/catalog/tools/tool4/');
            echo Html::input('text','alias',null,['placeholder'=>'alias']);
            echo Html::submitButton('Старт', ['class' => 'btn btn-primary']);
            echo Html::endForm();
            ?>
        </div>
    </div>
    <div class="col-lg-6">

    </div>
</div>
