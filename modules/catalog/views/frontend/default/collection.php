<?php
$bundle = \frontend\themes\shop\pageAssets\catalog\collection::register($this);
\common\modules\mods\mods_seo\components\seo::setMeta([
    'seo_title' => $page['seo_title'],
//    'seo_keyword' => $page['seo_keyword'],
    'seo_desc' => $page['seo_desc']
]);
use common\modules\catalog\components\Helper;
use yii\helpers\Html;

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
            <?php if (!empty($categoryUrl['title1'])): ?>
                <li><a href="<?= $categoryUrl['url1'] ?>"><?= $categoryUrl['title1'] ?></a></li>
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


        <div class="selector_view_product">
            <div class="toolbar selector_view_qnt">
                <p class="amount"><span style="color: #c21310"><?= count($products) ?></span>
                    <?= numberof(count($products), 'товар', array('', 'а', 'ов')) ?></p>
            </div>
        </div>
        <br/>


        <div class="cat_product_grid">

            <ul class="cat_product_grid">
                <?php if (!empty($products)): ?>
                    <?php
                    foreach ($products as $product): ?>
                        <li class="cat_product_item product_collection_item">
                            <div style="height: 200px;position: relative;">
                                <a href="<?= $product['url']; ?>">
                                    <?= Html::img('@web/statics/catalog/photo/images_small/' . $product['productIco'], [
                                        'class' => 'product_img', 'alt' => '',
                                        'id' => 'img_' . $product['id'],
                                    ]) ?>
                                </a>
                            </div>

                            <?php if ($product['in_stock'] > 0): ?>
                                <?php if ((($product['halva'] != 0) && empty($product['prices']['old'])) && !empty($product['prices']['min']) && empty($product['perekup_manufacturer'])) : ?>
                                    <?= Html::img('@web/statics/catalog/media/images/halva.png', ['alt' => 'Карта Халва', 'style' => 'position:absolute; height: 18px; bottom: 52px; right: 10px']) ?>
                                    <?= Html::img('@web/statics/catalog/media/images/card_turtle.png', ['alt' => 'Карта Черепаха', 'style' => 'position:absolute; height: 18px; bottom: 52px; right: 41px']) ?>
                                    <?= Html::img('@web/statics/web/site/files/karta_pokupok.png', ['alt' => 'Карта покупок', 'style' => 'position:absolute; height: 18px; bottom: 52px; right: 72px']) ?>
                                    <?= Html::img('@web/statics/web/site/files/smart_karta.png', ['alt' => 'Smart карта', 'style' => 'position:absolute; height: 18px; bottom: 52px; right: 103px']) ?>
                                    <?= Html::img('@web/statics/web/site/files/mocna_kartka.jpg', ['alt' => 'Моцная картка', 'style' => 'position:absolute; height: 18px; bottom: 52px; right: 134px']) ?>
                                    <?= Html::img('@web/statics/web/site/files/prior.png', ['alt' => 'Приорбанк-рассрочка', 'style' => 'position:absolute; height: 18px; bottom: 52px; right: 165px']) ?>
                                    <?= Html::img('@web/statics/web/site/files/magnit.png', ['alt' => 'Магнит', 'style' => 'position:absolute; height: 18px; bottom: 52px; right: 196px']) ?>
                                <?php endif; ?>
                            <?php endif; ?>
                            <?php if ($product['in_status'] == 3): ?>
                                <?= Html::img('@web/statics/catalog/media/images/akcia_exposicia.png', ['alt' => 'Товар с экспозиции', 'class' => 'element-expositions']) ?>
                            <?php endif; ?>
                            <?php if ($product['in_action'] == 1): ?>
                                <?= Html::img('@web/statics/catalog/media/images/akcia.png', ['alt' => 'Акционный товар', 'class' => 'element-action']) ?>
                            <?php endif; ?>

                            <div class="product_info">
                                <p class="product_name">
                                    <a href="<?= $product['url']; ?>"><?= $product['title'] ?></a>
                                </p>
                                <p class="product_manufact">
                                    <?= Html::img('@web/statics/catalog/country/images/' . $product['brandIco'],
                                        ['alt' => '', 'style' => 'position: relative;width:16px;']) ?>
                                    <a href="/manufacturer/<?= $product['brandAlias'] ?>/"><?= $product['brand'] ?></a>
                                    <?= $product['brandCountry'] ?>
                                </p>
                                <p style="padding:5px 0">Код товара: <span style="color:#000"><?= $product['article'] ?></span></p>



                                <div>
                                    <?php if (($product['in_stock'] > 0) OR (isset($product['children']))): ?>
                                    <?php if ($product['children']): ?>
                                        <div style="position: absolute; left: 10px; bottom: 10px">
                                            <p class="price new_price">
                                                <span style="font-size: 15px;"><?= $product['prices'] . ' ' . price($product['prices']) ?></span>
                                            </p>
                                        </div>
                                        <span style="position: absolute; right: 10px; bottom: 10px; color: #000;">
                                                <a href="<?= $product['url']; ?>">Показать<br/><?= count($product['children']) ?> моделей</a>
                                            </span>
                                    <?php else: ?>
                                        <div style="position:absolute;left:10px;bottom:10px;">
                                            <p class="price new_price">
                                                <span style="font-size: 15px;"><?= price($product['price']) ?></span>
                                            </p>
                                            <p class="price old_price">
                                                <span style="font-size: 14px;"><?= price($product['price_old'], false) ?></span>
                                            </p>
                                        </div>
                                        <span style="position:absolute;right:0;bottom:10px">
                                            <a href="<?= \yii\helpers\Url::to(['/shop/cart/add', 'id' => $product['id']]); ?>"
                                               data-id="<?= $product['id'] ?>" class="add-to-cart cart_smb btn smb"><i class="fa fa-shopping-cart"></i>В корзину
                                            </a>
                                        </span>
                                    <?php endif; ?>
                                    <?php else: ?>
                                        <div style="position:absolute;left:10px;bottom:10px;">
                                            <p class="price">Последняя цена: <?= price($product['price']) ?></p>
                                            <p class="price new_price">Скоро на складе</p>
                                        </div>
                                    <?php endif; ?>
                                </div>


                            </div>
                        </li>

                    <?php endforeach; ?>
                    <div class="clearfix"></div>
                <?php else : ?>
                    <h2>Здесь товаров пока нет...</h2>
                <?php endif; ?>
            </ul>

        </div>
    </div>


</div>
