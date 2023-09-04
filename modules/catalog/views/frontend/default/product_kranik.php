<?php
use yii\helpers\Html;

$bundle = \frontend\themes\shop\pageAssets\catalog\product::register($this);
\common\modules\mods\mods_seo\components\seo::setMeta([
    'seo_title' => $page['seo_title'],
//    'seo_keyword' => $page['seo_keyword'],
    'seo_desc' => $page['seo_desc']
]);
?>
<script>
    window.ProductPage = 1;
</script>

<!--<div id="productOverlay">-->
<!--    <img src="/statics/catalog/element/loading.gif"/>-->
<!--</div>-->
<!--<div id="product" style="display: none;">-->
    <input type="hidden" id="productId" value="<?= $productId ?>">

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
            <li class="active"><?= $productFull['title'] ?></li>
        </ul>
    </div>

    <h1 class="rubric akcent alpha page_h1">
        <span><?= $productPage['brand'] . ' ' . $productPage['title'] ?></span>
    </h1>
    <br/>

    <div class="panel panel-white">
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-5 col-md-5 col-sm-5" style="border-right:1px solid #ddd;">


                    <ul id="imageGallery" class="lightSlider lsGrab lSSlide"
                        style="width:1335px;height:403px;padding-bottom:0;transform:translate3d(0,0,0);">
                        <?php $i = 0; ?>
                        <?php foreach ($productFull['photos'] as $photos): ?>
                            <li data-thumb="<?= $photos['name_small'] ?>" class="lslide <?= ($i) ? 'active' : '' ?>" style="width:445px;margin-right:0;">
                                <img id="<?= $productFull['id'] ?>" src="<?= $photos['name'] ?>" href="<?= $photos['name'] ?>">
                            </li>
                            <?php $i++; endforeach; ?>
                    </ul>

                    <?php if ($productPage['status'] == 3): ?>
                        <?= Html::img('@web/statics/catalog/media/images/akcia_exposicia.png', ['alt' => 'Товар с экспозиции', 'class' => 'element-expositions', 'style' => 'height:100px;']) ?>
                    <?php endif; ?>
                    <?php if ($productPage['action'] == 1): ?>
                        <?= Html::img('@web/statics/catalog/media/images/akcia.png', ['alt' => 'Акционный товар', 'class' => 'element-action', 'style' => 'height:100px;']) ?>
                    <?php endif; ?>

                    <?php if ($productPage['brand'] == 'Grohe'): ?>
                        <a target="_blank"
                           href="/news/bolshe-pokupok-produkcii-grohe---bolshe-stilnyh-i-brendovyh-podarkov/">
                            <?= Html::img('@web/statics/site/files/podarok.png', ['alt' => 'Подарок', 'style' => 'position:relative;height:80px;bottom:135px;left:76%']) ?>
                        </a>
                    <?php endif; ?>
                    <?php if (($productPage['brand'] == 'JIKA') && ($productFull['category']['title'] == 'Унитазы подвесные' || 'Унитазы напольные (компакт)')): ?>
                        <a target="_blank" href="/news/podarok-za-pokupku-ot-proizvoditelya-jika/">
                            <?= Html::img('@web/statics/site/files/podarok.png', ['alt' => 'Подарок', 'style' => 'position:relative;height:80px;bottom:135px;left:76%']) ?>
                        </a>
                    <?php endif; ?>

                    <hr/>


                    <?php if ($productFull['manufacturer']['alias'] == 'geberit'): ?>
                        <p id="geberit_servis" class="complect_list_item"><a style="cursor:help">- Бесплатное гарантийное обслуживание -</a></p>
                        <p class="geberit_servis_hide">
                            <?= Html::img('@web/statics/catalog/media/images/geberit_hologram.jpg', ['alt' => 'Голограмма Geberit']) ?><br/>
                            <span style="color:#C21310">Внимание!</span> Только наличие оригинальной голограммы на упаковке инсталяции Geberit подтверждает бесплатное
                            гарантийное обслуживание - 10 лет. При отсутствии голограммы - изделие гарантии не подлежит.<br/><span style="color:#C21310">Будьте бдительны.</span></p>
                    <?php endif; ?>

                    <?php if ( 'triton' == ($productFull['manufacturer']['alias']) && 'akrilovye-vanny' == ($productFull['category']['alias']) ): ?>
                        <p class="box visible table-isset b-content" style="font-size:14px;">Вы можете приобрести гидромассаж к этой ванне <strong>"стандарт 6 форсунок"</strong>, по цене 635 руб.</p>
                    <?php endif; ?>

                    <!-- Комплектации -->
                    <?php if (!empty($productFull['complects'])): ?>
                        <br/>
                        <ul class="nav nav-tabs">
                            <li class="active" data-toggle="tab"><a id="blink7" href="#description" role="tab" data-toggle="tab"><strong>Сопутствующие товары</strong></a></li>
                        </ul>
                        <div>
                            <table class="table table-striped table-hover table-condensed">
                                <?php $count = 1; ?>
                                <?php foreach ($productFull['complects'] as $complect): ?>
                                    <tr<?php if ($count > 5) echo ' class="complect_list_hide"'; ?>>
                                        <td style="padding:10px 5px;width:70px;text-align:center;">
                                            <a href="<?= $complect['url'] ?>" target="_blank">
                                                <img src="/statics/catalog/photo/images_small/<?= $complect['img'] ?>" style="max-width:60px;max-height:140px;"/>
                                            </a>
                                        </td>
                                        <td style="padding:10px 5px;">
                                            <a href="<?= $complect['url'] ?>" target="_blank">
                                                <span style="color:#000;font-size:13px;font-weight:bold;"><?= $complect['title_before'] . ' ' . $complect['title'] ?></span>
                                                <?php if ($complect['vendor_code']): ?>
                                                    <p class="articul">Артикул: <span class="articul_no"><?= $complect['vendor_code'] ?></span></p>
                                                <?php endif; ?>
                                                <p class="articul">Код: <span class="articul_no"><?= $complect['code_1c'] ?></span></p>
                                            </a>

                                            <p class="articul">Производитель:
                                                <img src="/statics/catalog/manufacturer/images/<?= $complect['img_manufacturer'] ?>" style="max-width:25px;max-height:20px;">
                                                <span class="articul_no"><?= $complect['title_manufacturer'] . ' ' . $complect['country_manufacturer'] ?></span>
                                                <img src="/statics/catalog/country/images/<?= $complect['country_img'] ?>">
                                            </p>

                                            <?php if ($complect['in_stock'] > 0): ?>
                                                <?php if (($complect['halva'] != 0) && ($complect['price_old'] == 0) && empty($complect['perekup_manufacturer'])) : ?>
                                                    <div style="padding-top: 6px;">
                                                        <a href="/oplata/" target="_blank">
                                                            <?= Html::img('@web/statics/catalog/media/images/halva.png', ['alt' => 'Карта Халва', 'class' => 'cart_installments_product_complect']) ?>
                                                            <?= Html::img('@web/statics/catalog/media/images/card_turtle.png', ['alt' => 'Карта Черепаха', 'class' => 'cart_installments_product_complect']) ?>
                                                            <?= Html::img('@web/statics/web/site/files/karta_pokupok.png', ['alt' => 'Карта покупок', 'class' => 'cart_installments_product_complect']) ?>
                                                            <?= Html::img('@web/statics/web/site/files/smart_karta.png', ['alt' => 'Smart карта', 'class' => 'cart_installments_product_complect']) ?>
                                                            <?= Html::img('@web/statics/web/site/files/mocna_kartka.jpg', ['alt' => 'Моцная картка', 'class' => 'cart_installments_product_complect']) ?>
                                                            <?= Html::img('@web/statics/web/site/files/prior.png', ['alt' => 'Приорбанк-рассрочка', 'class' => 'cart_installments_product_complect']) ?>
                                                            <?= Html::img('@web/statics/web/site/files/magnit.png', ['alt' => 'Магнит', 'class' => 'cart_installments_product_complect']) ?>
                                                        </a>
                                                    </div>
                                                <?php endif; ?>

                                                <?php if ($complect['price'] > 50000): ?>
                                                    <div style="padding-top:6px;">
                                                        <a href="/dostavka/" target="_blank">
                                                            <?= Html::img('@web/statics/catalog/media/stock/delivery_belarus.png', ['alt' => 'Доставка по Беларуси', 'style' => 'height:25px;']) ?>
                                                        </a>
                                                    </div>
                                                <?php elseif ($complect['price'] > 12000): ?>
                                                    <div style="padding-top:6px;">
                                                        <a href="/dostavka/" target="_blank">
                                                            <?= Html::img('@web/statics/catalog/media/stock/delivery_minsk.png', ['alt' => 'Доставка по Минску', 'style' => 'height:25px;']) ?>
                                                        </a>
                                                    </div>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </td>
                                        <td style="padding:10px 5px;width:128px;">
                                            <?php if ($complect['in_stock'] > 0): ?>
                                                <div style="padding-bottom:6px;text-align:center;">
                                                    <p class="price new_price"><?= price($complect['price']) ?></p>
                                                    <?php if ($complect['price_old'] > $complect['price']): ?>
                                                        <p class="price old_price"><?= price($complect['price_old'], false) ?></p>
                                                    <?php endif; ?>
                                                </div>
                                                <a href="<?= \yii\helpers\Url::to(['/shop/cart/add', 'id' => $complect['id']]) ?>"
                                                   data-id="<?= $complect['id'] ?>" class="add-to-cart cart_smb btn smb">
                                                    <i class="fa fa-shopping-cart"></i>В корзину
                                                </a>
                                            <?php else: ?>
                                                <p class="price">Последняя цена: <?= price($complect['price'], false) ?></p>
                                                <p class="price new_price">Скоро на складе</p>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php $count++; ?>
                                <?php endforeach; ?>
                            </table>

                            <?php if ($count > 6): ?>
                                <p id="complect_list_btn" class="complect_list_item"><a style="cursor:help">- Показать ещё -</a></p>
                            <?php endif; ?>

                        </div>
                        <br/>
                    <?php endif; ?>
                    <!-- /комплектации -->

                </div>

                <div class="col-lg-7 col-md-7 col-sm-7">
                    <div style="display:table-row;">
                        <a href="<?= $productFull['manufacturer']['url'] ?>" class="brand-logo">
                            <img src="/statics/catalog/manufacturer/images/<?= $productFull['manufacturer']['img'] ?>">
                        </a>
                        <span class="description_head"><?= $productFull['manufacturer']['country'] ?>
                            <img src="/statics/catalog/country/images/<?= $productFull['manufacturer']['ico'] ?>">
                        </span>
                    </div>

                    <!-- Модели -->
                    <?php if (!empty($productFull['models'])): ?>
                        <div style="padding-top:10px;">
                            <ul class="nav nav-tabs">
                                <li class="active" data-toggle="tab"><a href="#description" role="tab" data-toggle="tab"><strong>Модели</strong></a></li>
                            </ul>

                            <?php if ($selected_parent_product): ?>
                            <p class="new_price" style="margin:0 0 15px 10px;font-size:14px;">Выберите модель</p>
                            <?php endif; ?>

                            <ul class="product_model" style="display:block;min-height:31px;">
                                <?php foreach ($productFull['models'] as $item): ?>
                                    <li>
                                        <a href="<?= $item['url'] ?>">
                                            <span<?= ($item['isActive'] == 1) ? ' class="active"' : '' ?>><?= $item['title'] ?></span>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    <div class="clearfix"></div>

                    <?php if (!$selected_parent_product): ?>
                        <table class="table table-striped table-hover table-condensed product_to_cart" style="margin:10px 0;font-size:14px;">
                            <tr>
                                <th><?= $productFull['manufacturer']['title'] . ' ' . $productFull['title_before'] ?> <b><?= $productFull['title'] ?></b></th>
                            </tr>
                            <?php if ($productFull['vendor_code']): ?>
                                <tr>
                                    <td>Артикул: <span class="articul_no"><?= $productFull['vendor_code'] ?></span></td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <td>Код: <span class="articul_no"><?= $productFull['article'] ?></span></td>
                            </tr>
                            <?php if ($productFull['info_manufacturer']): ?>
                                <tr>
                                    <td>Страна производства: <span class="articul_no"><?= $productFull['info_manufacturer'] ?></span></td>
                                </tr>
                            <?php endif; ?>

                            <?php if ($guarantee): ?>
                                <tr>
                                    <td>Гарантийный срок: <span class="articul_no"><?= $guarantee ?> <?= numberof($guarantee, 'месяц', ['', 'а', 'ев']); ?></span></td>
                                </tr>
                            <?php endif; ?>
                        </table>
                    <?php endif; ?>

                    <div style="display:table-row;">
                        <?php if (!$selected_parent_product): ?>
                            <?php if ($productPage['in_stock'] > 0): ?>
                                <input class="form-control product_in_stock" id="qty" type="number" min="1" name="number" value="1">
                                <a href="<?= \yii\helpers\Url::to(['/shop/cart/add', 'id' => $productId]) ?>"
                                   data-id="<?= $productId ?>" class="add-to-cart cart_smb btn smb"><i class="fa fa-shopping-cart"></i>В корзину
                                </a>
                                <?php if ($productFull['price_old'] > $productFull['price']): ?>
                                    <div style="display:table-cell;vertical-align:middle">
                                        <p class="price new_price"><?= price($productFull['price']) ?></p>
                                        <p class="price old_price"><?= price($productFull['price_old'], false) ?></p>
                                    </div>
                                <?php else: ?>
                                    <p class="price_big new_price"><?= price($productFull['price']) ?></p>
                                <?php endif; ?>
                            <?php else: ?>
                                <p class="price">Последняя цена: <?= price($productFull['price']) ?></p>
                                <p class="price new_price">Скоро на складе</p>
                            <?php endif; ?>
                            <div class="clearfix"></div>
                        <?php endif; ?>
                    </div>

                    <!-- Комплект -->
                    <?php if (!empty($productFull['kits'])): ?>
                        <?php foreach ($productFull['kits'][1]['item'] as $item): ?>
                            <!--<table class="product_to_cart" style="padding-top: 10px;">-->
                            <table class="table table-striped table-hover table-condensed product_to_cart" style="margin:10px 0;font-size:13px;">
                                <tr>
                                    <th><b><?= $item['title'] ?></b></th>
                                </tr>
                                <?php if ($item['vendor_code']): ?>
                                    <tr>
                                        <td>Артикул: <span class="articul_no"><?= $item['vendor_code'] ?></span></td>
                                    </tr>
                                <?php endif; ?>
                                <tr>
                                    <td>Код: <span class="articul_no"><?= $item['code'] ?></span></td>
                                </tr>
                            </table>

                            <div style="display:table-row;">
                                <?php if ($item['in_stock'] > 0): ?>
                                    <input class="form-control product_in_stock" id="qty" type="number" min="1" name="number" value="1">
                                    <a href="<?= \yii\helpers\Url::to(['/shop/cart/add', 'id' => $item['id']]) ?>"
                                       data-id="<?= $item['id'] ?>" class="add-to-cart cart_smb btn smb"><i class="fa fa-shopping-cart"></i>В корзину
                                    </a>
                                    <?php if ($item['price_old'] > $item['price']): ?>
                                        <div style="display:table-cell;vertical-align:middle">
                                            <p class="price new_price"><?= price($item['price']) ?></p>
                                            <p class="price old_price"><?= price($item['price_old'], false) ?></p>
                                        </div>
                                    <?php else: ?>
                                        <p class="price_big new_price"><?= price($item['price']) ?></p>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <p class="price">Последняя цена: <?= price($item['price']) ?></p>
                                    <p class="price new_price">Скоро на складе</p>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                        <hr>
                        <p class="price" style="padding:10px 0;">Итого стоимость комплекта:
                            <span class="new_price" style="font-size:20px;"><?= price($productFull['kits'][1]['price_sum']) ?></span>
                        </p>
                        <hr>
                    <?php endif; ?>
                    <div class="clearfix"></div>


                    <?php if ($productPage['in_stock'] > 0): ?>
                        <?php if (($productPage['halva'] != 0) && ($productPage['price_old'] == 0) && empty($productPage['perekup_manufacturer'])) : ?>
                            <a href="/oplata/" target="_blank">
                                <?= Html::img('@web/statics/catalog/media/images/halva.png', ['alt' => 'Карта Халва', 'class' => 'cart_installments_product']) ?>
                                <?= Html::img('@web/statics/catalog/media/images/card_turtle.png', ['alt' => 'Карта Черепаха', 'class' => 'cart_installments_product']) ?>
                                <?= Html::img('@web/statics/web/site/files/karta_pokupok.png', ['alt' => 'Карта покупок', 'class' => 'cart_installments_product']) ?>
                                <?= Html::img('@web/statics/web/site/files/smart_karta.png', ['alt' => 'Smart карта', 'class' => 'cart_installments_product']) ?>
                                <?= Html::img('@web/statics/web/site/files/mocna_kartka.jpg', ['alt' => 'Моцная картка', 'class' => 'cart_installments_product']) ?>
                                <?= Html::img('@web/statics/web/site/files/prior.png', ['alt' => 'Приорбанк-рассрочка', 'class' => 'cart_installments_product']) ?>
                                <?= Html::img('@web/statics/web/site/files/magnit.png', ['alt' => 'Магнит', 'class' => 'cart_installments_product']) ?>
                            </a>
                        <?php endif; ?>

                        <?php if ($productPage['price'] > 50000): ?>
                            <a href="/dostavka/" target="_blank">
                                <?= Html::img('@web/statics/catalog/media/stock/delivery_belarus.png', ['alt' => 'Доставка по Беларуси', 'style' => 'height: 40px;margin:10px 5px 0 0;']) ?>
                            </a>
                        <?php elseif ($productPage['price'] > 12000): ?>
                            <a href="/dostavka/" target="_blank">
                                <?= Html::img('@web/statics/catalog/media/stock/delivery_minsk.png', ['alt' => 'Доставка по Минску', 'style' => 'height: 40px;margin:10px 5px 0 0;']) ?>
                            </a>
                        <?php endif; ?>
                        <br/>
                    <?php endif; ?>

                    <!-- Описание -->
                    <?php if (!empty($productFull['description'])): ?>
                        <br>
                        <ul class="nav nav-tabs">
                            <li class="active" data-toggle="tab"><a href="#description" role="tab" data-toggle="tab"><strong>Описание</strong></a></li>
                        </ul>
                        <div class="box visible table-isset b-content" itemprop="description">
                            <?= $productFull['description'] ?>
                        </div>
                    <?php endif; ?>
                    <!-- /описание -->

                    <!-- Фильтры -->
                    <?php if (count($productFull['groups'])): ?>
                        <br/>
                        <ul class="nav nav-tabs">
                            <li class="active" data-toggle="tab"><a href="#description" role="tab" data-toggle="tab"><strong>Характеристики</strong></a></li>
                        </ul>
                        <table class="table table-striped table-hover table-condensed">
                            <?php foreach ($productFull['groups'] as $group): ?>
                                <tr>
                                    <td colspan="2" class="col-xs-12" style="background-color:rgba(33,33,33,0.2);"><?= $group['title'] ?></td>
                                </tr>
                                <?php foreach ($group['fields'] as $fields): ?>
                                    <tr>
                                        <td class="col-xs-4"><?= $fields['title'] ?></td>
                                        <td class="col-xs-8"><?= $fields['result'] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        </table>
                    <?php endif; ?>
                    <!-- /Фильтры -->

                    <!-- Инструкции -->
                    <?php if (!empty($productFull['instructions'])): ?>
                        <br><br/><br/>
                        <ul class="nav nav-tabs">
                            <li class="active" data-toggle="tab"><a href="#description" role="tab" data-toggle="tab"><strong>Инструкции</strong></a></li>
                        </ul>
                        <table class="table table-striped table-hover table-condensed">
                            <?php foreach ($productFull['instructions'] as $instruction): ?>
                                <tr>
                                    <td class="col-xs-12" style="color:#000;font-size:13px;font-weight:bold;">Скачать инструкцию:
                                        <a target="_blank" href="<?= $instruction['url'] ?>"><span style="color:#7c7c7c;font-size:13px;font-weight:normal;"><?= $instruction['name'] ?></span></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    <?php endif; ?>
                    <!-- /инструкции -->

                    <br/>
                    <!--noindex-->
                    <small>
                        <em>
                            Данная информация о товаре предоставлена исключительно в целях ознакомления и не является публичной офертой. Наш интернет-магазин использует данные о товарах, представленные на официальных сайтах производителей и поставщиков, а также в документации к товару. Однако, производители оставляют за собой право изменять внешний вид, характеристики и комплектацию изделий, предварительно не уведомляя продавцов и потребителей. При совершении покупки, убедительная просьба, уточнять интересующие вас сведения о товаре до оформления заказа.
                        </em>
                    </small>
                    <!--/noindex-->		
					
                </div>
            </div>

        </div>
    </div>
<!--</div>-->