<?php
$bundle = \frontend\themes\shop\pageAssets\catalog\manufacturer::register($this);
?>

<div class="panel panel-flat" xmlns="http://www.w3.org/1999/html">
    <div class="panel-heading">
        <div class="row">
            <div class="hidden-xs hidden-sm col-md-3 col-lg-3">
                <?= $this->render('_manufacturerMenu');?>
            </div>
            <div class="col-md-9 col-lg-9 manufacturerPage">
                <!-- Бренды -->
                <? if (is_array($manufacturers) && count($manufacturers)) { ?>
                <div>
                    <h5>Бренды</h5>
                    <div>
                        <? foreach($manufacturers as $manufacturer) { ?>
                        <a href="/catalog/manufacturer/<?=$manufacturer['alias'];?>">
                            <div class="manufacturerBlock">
                                <img src="/statics/catalog/manufacturer/images/<?=$manufacturer['ico'];?>">
                                <span><?=$manufacturer['title'];?></span>
                            </div>
                        </a>
                        <? } ?>
                    </div>
                </div>
                <? } ?>
                <!-- /бренды -->
            </div>
        </div>
    </div>
</div>





