<?php
$bundle = \frontend\themes\shop\pageAssets\catalog\manufacturer::register($this);

$countries = $page;
$page = $page['0'];

\common\modules\mods\mods_seo\components\seo::setMeta([
    'seo_title' => $page['seo_title'],
    'seo_keyword' => $page['seo_keyword'],
    'seo_desc' => $page['seo_desc']
]);
?>

<div class="panel panel-flat" xmlns="http://www.w3.org/1999/html">
    <div class="panel-heading">
        <div class="row">
<!--            <div class="col-sm-3 col-md-3 visible-lg visible-md hidden-sm ">-->
<!--                --><?//= $this->render('_manufacturerMenu');?>
<!--            </div>-->
            <div class="col-sm-12 col-md-12 col-lg-12 manufacturerPage">

                <div class="row">
                    <div class="col-md-6">
                        <h5>
                            <!-- Название и логотип -->
                            <? if ($page['ico']) { ?><img class="manufacturerPage-logo" src="/statics/catalog/manufacturer/images/<?=$page['ico'];?>"><? } ?>
                            <!-- /название и логотип -->
                        </h5>
                    </div>
                    <div class="col-md-6 text-right">
                             <!-- Страна(ы) производства -->
                            <h5>
                                <?
                                if (count($countries)) {
                                    foreach($countries as $country) {
                                        echo'<img class="manufacturerPage-logo" src="/statics/catalog/country/images/'.$country['country_ico'].'"> '.$country['country_title'].'&nbsp;';
                                    }
                                }
                                ?>
                            </h5>
                            <!-- /страна(ы) производства -->
                    </div>
                </div>

                <!-- Описание бренда -->
                <? if ($page['desc']) { ?>
                    <div>
                        <?=$page['desc'];?>
                    </div>
                <? } ?>
                <!-- /описание бренда -->

                <hr class="clear mb-20 manufacturerHr"/>

                <!-- Категории бренда -->
                <? if (is_array($categories) && count($categories)) { ?>
                <h5>Категории с товарами бренда <?=$page['title'];?></h5>
                <div>
                    <? foreach ($categories as $category) { ?>
                    <a href="<?=$category['alias'] . '/?mfr[0]=' . $page['alias'] . '&mfr[operation]=union' ?>/">
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

            </div>
        </div>
    </div>
</div>