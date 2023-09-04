<?php
use yii\helpers\Html;

?>
<div class="row">
    <div class="col-lg-6">
        <p><?=$mes;?></p>
        <p>Step 2</p>
        <div>
            <?
            echo Html::beginForm('/backend/catalog/tools/tool2/');
            echo Html::input('text','alias',null,['placeholder'=>'alias']);
            echo Html::submitButton('Старт', ['class' => 'btn btn-primary']);
            echo Html::endForm();
            ?>
        </div>
    </div>
    <div class="col-lg-6">

    </div>
</div>
