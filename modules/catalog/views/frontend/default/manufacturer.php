<?php
$bundle = \frontend\themes\shop\pageAssets\catalog\manufacturer::register($this);
?>

<div class="panel panel-flat" xmlns="http://www.w3.org/1999/html">
    <div class="panel-heading">
        <div class="row">
            <div class="hidden-xs hidden-sm col-md-3 col-lg-3">
                <h5><? if ($page['ico']) { ?><img src="/statics/catalog/manufacturer/images/<?=$page['ico'];?>"><? } else { echo $page['title']; } ?></h5>
                <?= $this->render('_manufacturerMenu');?>
            </div>
            <div class="col-md-9 col-lg-9 manufacturerPage">
                <!-- Категории бренда -->
                <h5>Категории с товарами бренда <?=$page['title'];?></h5>
                <div>
                <? if (is_array($categories) && count($categories)) { ?>
                    <? foreach ($categories as $category) { ?>
                    <a href="/catalog/<?=$category['alias'];?>/">
                        <div class="catelogBlock">
                            <div class="catelogImg">
                                <img src="<? echo ($category['ico']) ? '/statics/catalog/category/images/'.$category['ico'] : '/statics/catalog/category/no-image.jpg'; ?>">
                            </div>
                            <div class="catelogTitle">
                                <div><?=$category['title'];?></div>
                            </div>
                        </div>
                        </a>
                    <? } ?>
                <? } ?>
                </div>
                <!-- /категории бренда -->

                <hr class="clear mb-20 manufacturerHr"/>

                <!-- Описание бренда -->
                <? if ($page['desc']) { ?>
                    <div>
                        <h5>Описание бренда</h5>
                        <?=$page['desc'];?>
                    </div>
                    <hr class="clear mb-10"/>
                <? } ?>
                <!-- /описание бренда -->

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





