<?php
$bundle = \frontend\themes\shop\pageAssets\catalog\manufacturer::register($this);
?>

<div class="panel panel-flat" xmlns="http://www.w3.org/1999/html">
    <div class="panel-heading">
        <div class="row">
<!--            <div class="col-sm-3 col-md-3 col-lg-3 ">-->
<!--                --><?//= $this->render('_manufacturerMenu');?>
<!--            </div>-->
            <div class="col-sm-12 col-md-12 col-lg-12 manufacturerPage">
                <!-- Бренды -->
                <? if (is_array($manufacturers) && count($manufacturers)) { ?>
                <div>
                    <h5>Бренды</h5>
                    <br/>
                    <div>
                        <? foreach($manufacturers as $manufacturer) { ?>
                        <a href="/manufacturer/<?=$manufacturer['alias'];?>/">
                            <div class="manufacturerBlock">
                                <img src="/statics/catalog/manufacturer/images/<?=($manufacturer['ico']) ? $manufacturer['ico'] : '_no.png';?>">
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