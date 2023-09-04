<?php
$bundle = \frontend\themes\shop\pageAssets\catalog\manufacturer::register($this);

$page = $page['0'];

\common\modules\mods\mods_seo\components\seo::setMeta([
    'seo_title' => $page['seo_title'],
    'seo_desc' => $page['seo_desc']
]);
?>

<div class="catalog-content js-scrolling-area">

    <div class="breadcrumb-line">
        <ul class="breadcrumb breadcrumb_upd">
            <li><a href="/">Главная</a></li>
            <li><a href="/brand/">Бренды</a></li>
            <li class="active"><?= mb_strtoupper($page['title']) ?></li>
        </ul>
    </div>

        <div class="brand-logo">
            <? if ($page['ico']) { ?><img class="manufacturerPage-logo" src="/statics/catalog/manufacturer/images/<?= $page['ico']; ?>" alt=""><? } ?>
        </div>
        <h1 class="rubric akcent alpha"><i></i><?= mb_strtoupper($page['title']) ?> <?= $page['country_title'] ?></h1>


        <div class="panel panel-flat" style="border: 0">
            <div class="panel-heading" style="padding: 20px 0">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12 manufacturerPage">
                        <!-- Описание бренда -->
                        <? if ($page['desc']) { ?>
                            <div>
                                <?= $page['desc']; ?>
                            </div>
                            <hr class="clear mb-20 manufacturerHr"/>
                        <? } ?>
                        <!-- /описание бренда -->


                        <!-- Категории бренда -->
                        <? if (is_array($categories) && count($categories)) { ?>
                            <h5>Категории с товарами бренда <?= $page['title']; ?></h5>
                            <div>
                                <? foreach ($categories as $category) { ?>
                                    <a href="<?= $category['alias'] . '/?mfr[0]=' . $page['alias'] . '&mfr[operation]=union' ?>/">
                                        <div class="catelogBlock">
                                            <div class="catelogImg">
                                                <img src="<? echo ($category['ico']) ? '/statics/catalog/category/images/' . $category['ico'] : '/statics/catalog/category/no-image.jpg' ?>">
                                            </div>
                                            <div class="catelogTitle">
                                                <div><?= $category['title'] ?></div>
                                            </div>
                                        </div>
                                    </a>
                                <? } ?>
                            </div>
                        <? } ?>
                        <!-- /категории бренда -->

                    </div>
                </div>
            </div>
        </div>
    </div>

<br/>
