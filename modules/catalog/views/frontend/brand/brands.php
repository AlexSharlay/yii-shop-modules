<?php
//$bundle = \frontend\themes\shop\pageAssets\catalog\manufacturer::register($this);

\common\modules\mods\mods_seo\components\seo::setMeta([
    'seo_title' => 'Бренды сантехники',
//    'seo_desc' => $page['seo_desc']
]);
?>


<div class="catalog-content js-scrolling-area">

    <div class="breadcrumb-line">
        <ul class="breadcrumb breadcrumb_upd">
            <li><a href="/">Главная</a></li>
            <li class="active">
                Бренды сантехники
            </li>
        </ul>
    </div>

    <div class="main_content_boo">

        <div class="warp">
            <h1 class="rubric akcent alpha page_h1">
                <span>Бренды сантехники</span>
            </h1>
            <br/>

            <?php if (!empty($manufacturers)): ?>
                <div class="cat_grid">
                    <ul class="cat_grid">
                        <?php foreach ($manufacturers as $item): ?>
                            <li class="brands">
                                <a href="/brand/<?= $item['alias'] ?>/">
                                    <?php if ($item['ico']): ?>
                                        <img class="brand_img" src="/statics/catalog/manufacturer/images/<?= $item['ico'] ?>" alt=""/>
                                    <?php endif; ?>
<!--                                    <span class="category_pselink"><span>--><?//= $item['title'] ?><!--</span></span>-->
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="clearfix"></div>
            <?php endif; ?>

        </div>
    </div>

</div>