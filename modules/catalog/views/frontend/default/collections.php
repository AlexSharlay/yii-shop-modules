<?php
$bundle = \frontend\themes\shop\pageAssets\catalog\collections::register($this);
\common\modules\mods\mods_seo\components\seo::setMeta([
    'seo_title' => $page['seo_title'],
    'seo_desc' => $page['seo_desc']
]);
?>


<div class="catalog-content js-scrolling-area">

    <div class="breadcrumb-line">
        <ul class="breadcrumb breadcrumb_upd">
            <li><a href="/">Главная</a></li>
            <?php if (!empty($categoryUrl['title3'])): ?>
                <li><a href="<?= $categoryUrl['url3'] ?>"><?= $categoryUrl['title3'] ?></a></li>
            <?php endif; ?>
            <?php if (!empty($categoryUrl['title2'])): ?>
                <li><a href="<?= $categoryUrl['url2'] ?>"><?= $categoryUrl['title2'] ?></a></li>
            <?php endif; ?>
            <li class="active">
                <?= $page['title'] ?>
            </li>
        </ul>
    </div>


    <div class="warp">
        <h1 class="rubric akcent alpha page_h1">
            <span><?= $page['title'] ?></span>
        </h1>
        <br/>

        <?php if (!empty($categoriesForMenuImg)): ?>
            <div class="cat_grid">
                <ul class="cat_grid">
                    <?php foreach ($categoriesForMenuImg as $item): ?>
                        <li class="cat_collections">
                            <a href="<?= $item['url'] ?>">
                                <?php if ($item['ico']): ?>
                                    <img class="category_img" src="<?= $item['ico'] ?>" alt=""
                                         style="max-height: 140px; max-width: 140px;"/>
                                <?php endif; ?>
                                <span class="category_pselink"><span><?= $item['title'] ?></span></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="clearfix"></div>
        <?php endif; ?>

    </div>
</div>