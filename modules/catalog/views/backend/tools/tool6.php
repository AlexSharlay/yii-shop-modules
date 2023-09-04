<?php
use yii\helpers\Html;

?>
<div class="row">
    <div class="col-lg-12">
        <div style=" word-wrap: break-word;">
            <?php
            echo '<pre>';
            print_r($mes);
            echo '</pre>';
            ?>
        </div>
        <p>Tool 6</p>
        <div>
            <?
            echo Html::beginForm('/backend/catalog/tools/tool6/');
            echo Html::input('text','alias',null,['placeholder'=>'alias']);
            echo Html::submitButton('Старт', ['class' => 'btn btn-primary']);
            echo Html::endForm();
            ?>
        </div>
    </div>

</div>
