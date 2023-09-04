<?php
$bundle = \frontend\themes\shop\pageAssets\catalog\product::register($this);
\common\modules\mods\mods_seo\components\seo::setMeta([
    'seo_title' => $page['seo_title'],
    'seo_keyword' => $page['seo_keyword'],
    'seo_desc' => $page['seo_desc']
]);
?>
<script>
    window.ProductPage = 1;
</script>

<div id="productOverlay">
    <img src="/statics/catalog/element/loading.gif"/>
</div>
<div id="product" style="display: none;">
    <input type="hidden" id="productId" value="<?=$productId;?>">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <!--li><a href="/catalog">Каталог</a></li-->
            <li><a data-bind="text : product.category['title'], attr: { href: '/catalog/' + product.category['alias']()+ '/' }"></a></li>
            <li><a data-bind="text : product.manufacturer['title'], attr: { href: '/catalog/' + product.category['alias']() + '/?mfr[0]=' + product.manufacturer['alias']() }"></a></li>
            <li class="active" data-bind="text : product.title"></li>
        </ul>
    </div>
    <div class="panel panel-white">
        <div class="panel-heading">
            <h1 class="panel-title"><span data-bind="text : product.manufacturer['title']"></span> <span data-bind="text : product.title"></span></h1>
            <div class="heading-elements" >
                <span>Код: </span><span data-bind="text : product.article"></span>
                <?php if(isset($vendor_code)): ?>
                    <br/>
                    <span>Артикул: </span><?=$vendor_code;?>
                <?php endif;?>
            </div>

        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-12" style="border-right: 1px solid #ddd;">
                    <!-- Картинки -->
                    <div data-bind="if: product.photos">
                        <ul id="imageGallery" data-bind="foreach: {data:product.photos, as : 'photo' }">
                            <li data-bind="attr: { 'data-thumb' : photo.name }">
                                <img data-bind="attr: { 'src' : photo.name, 'href' : photo.name }"/>
                            </li>
                        </ul>
                    </div>
                    <!-- /картинки -->
                    <!-- Доставка и оплата -->
                    <? /*
                        // Розница
                        <div data-bind="if : product.delivery().length > 0">
                            <div class="panel-heading">
                                <h5 class="panel-title product_delivery_title">Доставка</h5>
                            </div>
                            <div data-bind="foreach: { data: product.delivery, as: 'delivery'}" class="mt-10">
                                <div class="col-lg-6">
                                    <p><span data-bind="text : delivery['title']"></span> (<span data-bind="text : delivery['price']"></span>)</p>
                                    <ul class="" data-bind="foreach: {data: delivery['payments'], as: 'payments'}">
                                        <li data-bind="text: payments['title']"></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        */ ?>
                    <? if ($day) { ?>
                        <hr/>
                        <div>
                            <div class="panel-heading">
                                <h6 class="panel-title ">Дни доставки для Вашего города <?=$city;?>:<br/><?=$day;?></h6>
                            </div>
                        </div>
                    <? } ?>
                    <!-- /доставка и оплата-->
                    <!-- Гарантия -->

                    <div data-bind="if: product.guarantee">
                        <hr/>
                        <div class="panel-heading">
                            <h5 class="panel-title product_garant_title"><span>Гарантия:</span> <span data-bind="text : product.guarantee"></span> мес.</h5>
                        </div>
                    </div>
                    <!-- /гарантия -->
                </div>
                <div class="col-lg-8 col-md-8 col-sm-6">
                    <!-- Модели -->
                    <div data-bind="ifnot: product.models().length == 0">
                        <p class="product_model_title">Модели:</p>
                        <ul class="product_model" data-bind="foreach: { data: product.models, as: 'model'}" style="display: block;min-height: 31px;">
                            <li data-bind="click : function() {vmProduct.getProduct(model.id)}">
                                <span data-bind="text : model.title, css: { 'active' : model.isActive }"></span>
                            </li>
                        </ul>
                    </div>
                    <!-- /модели -->
                    <!-- Сборка -->
                    <div data-bind="ifnot: product.kits().length == 0">
                        <p class="product_kit_title">Сборки:</p>
                        <ul class="product_kit" data-bind="foreach: {data: product.kits, as : 'kit'}">
                            <li data-bind="click : function() {vmProduct.getProduct(kit.id_product,kit.id_kit)}, css: { 'active' : kit.isActive }">
                                <div class="product_kit_head" data-bind="text : 'Сборка '+($index()+1) "></div>
                                <ul class="product_kit_list" data-bind="ifnot: kit.item">
                                    <li data-bind="text: 'Без дополнительных комплектующих'"></li>
                                </ul>
                                <ul class="product_kit_list plus" data-bind="foreach: kit.item">
                                    <li data-bind="text: title"></li>
                                </ul>
                                <br/>
                                <div data-bind="ifnot: kit.item" style="text-align: right">
                                    = <span data-bind="text : formatCurrency(kit.price_sum)"></span><span class=""> Br</span><br/>
                                </div>
                                <div data-bind="ifnot: kit.price == 0, ifnot: kit.price == null" style="text-align: right">
                                    + <span data-bind="text : formatCurrency(kit.price)"></span><span class=""> Br</span><br/>
                                    = <span data-bind="text : formatCurrency(kit.price_sum)"></span><span class=""> Br</span><br/>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <!-- /сборка -->
                    <!-- Цена -->
                    <div data-bind="if: product.price()" style="clear:both;">
                        <input class="form-control product_in_stock" id="count" type="number" min="1" name="number" value="1"><? /* data-bind="attr: { max: product.in_stock }"> */ ?>
                        <? if (\Yii::$app->user->isGuest) { ?>
                            <button type="button" class="btn btn-primary btn-sm ml-5 mr-10" onclick="javascript: location.href='/signup/'">Купить</button>
                        <? } else { ?>
                            <button type="button" id="productAddCart" class="btn btn-primary btn-sm ml-5 mr-10" data-bind="click: function() { vmCart.addProduct(product); }">Купить</button>
                        <? } ?>
                        <span class="product_price">
                                <span class="product_price_sum" data-bind="text: formatCurrency(product.price)"></span>
                            <!--span class="product_price_m">Br</span-->
                            <?
                            /* Сказали измерения скрыть.
                            <!-- ko if: product.measurement.title -->
                            <!-- ko if: product.measurement.code -->
                                &nbsp - &nbsp
                                <span class="product_measurement" data-bind="text: product.measurement.code"></span>
                            <!-- /ko -->
                            <!-- /ko -->
                             */
                            ?>
                            </span>
                    </div>
                    <!-- /цена -->
                    <!-- Комплектации -->
                    <div data-bind="ifnot: product.complects().length == 0">
                        <hr/>
                        <p class="product_complect_title">Комплектующие:</p>
                        <ul class="product_complect" data-bind="foreach: {data: product.complects, as : 'complects'}">
                            <li><a data-bind="text : complects.title, attr: {'href': complects.url, 'target': '_blank'}"></a></li>
                        </ul>
                    </div>
                    <!-- /комплектации -->
                    <!-- Описание -->
                    <div data-bind="if: product.description">
                        <hr/>
                        <div data-bind="html: product.description"></div>
                    </div>
                    <!-- /описание -->

                    <!-- табы -->
                    <ul class="nav nav-tabs nav-justified mb-10" role="tablist">
                        <li class="active" data-toggle="tab"><a href="#description" role="tab" data-toggle="tab"><strong>Описание</strong></a></li>
                        <li data-toggle="tab">
                            <a href="#reviews" role="tab" data-toggle="tab">
                                <strong>Отзывы</strong>
                                <input type="text" class="kv-gly-star rating-loading" value="0" data-size="xl" title="" data-show-clear="false" data-show-caption="false" style="display: none">
                            </a>
                        </li>
                    </ul>
                    <!-- ko if: rating.ratingCount -->
                    <div itemprop="aggregateRating" itemscope="" itemtype="http://schema.org/AggregateRating">
                        <meta itemprop="reviewCount" data-bind="attr: { 'content' : 'Отзывов: ' + rating.ratingCount() }"/>
                        <meta itemprop="ratingValue" data-bind="attr: { 'content' : rating.ratingValue }"/>
                        <meta itemprop="bestRating" data-bind="attr: { 'content' : rating.bestRating }"/>
                    </div>
                    <!-- /ko -->
                    <!-- /табы -->

                    <div class="tab-content">
                        <div class="tab-pane active" id="description" role="tabpanel">
                            <!-- Характеристики -->
                            <div data-bind="if: product.groups().length > 0">
                                <hr/>
                                <div class="tabbable">
                                    <!--<div class="panel-heading">
                                        <h5 class="panel-title text-bold">Описание</h5>
                                    </div>-->
                                    <div class="table-responsive" data-bind="foreach: { data: product.groups, as: 'group'}">
                                        <table class="table product_property">
                                            <thead>
                                            <tr class="row">
                                                <th class="col-xs-12" colspan="2" data-bind="text: group.title"></th>
                                            </tr>
                                            </thead>
                                            <tbody data-bind="foreach: { data: group.fields, as: 'field'}">
                                            <tr class="row">
                                                <td class="col-xs-6" data-bind="text: field.title"></td>
                                                <td class="col-xs-6" data-bind="html: field.result"></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="row mt-20">
                                    </div>
                                    <small>
                                        <em>
                                            Информация взята из открытых источников и может содержать ошибки.
                                            Пожалуйста, перед покупкой товара, уточняйте характеристики у менеджера, а также проверяйте товар при получении.
                                        </em>
                                    </small>
                                </div>
                            </div>
                            <!-- /характеристики -->
                        </div>
                        <div class="tab-pane" id="reviews" role="tabpanel">
                            <div class="row mb-10 mr-5">
                                <!-- ko if: rating.ratingValue -->
                                <span data-bind="text : rating.ratingValue" class="pull-right"></span>
                                <span class="pull-right">Средняя оценка:&nbsp;</span>
                                <!-- /ko -->
                            </div>
                                <div data-bind="if: product.reviews().length > 0">
                                    <div data-bind="foreach: { data: product.reviews, as: 'review'}">
                                        <div class="panel panel-flat reviews-item">
                                            <div class="panel-heading">
                                                <h6 class="panel-title">
                                                    <span data-bind="foreach: [5,4,3,2,1]">
                                                        <i data-bind="css: {
                                                            'glyphicon' : true,
                                                            'glyphicon-star' : review.rating() >= $index()+1,
                                                            'glyphicon-star-empty' : review.rating() < $index()+1,
                                                        }" ></i>
                                                    </span>
                                                    <span data-bind="text: review.name"></span>
                                                    <div class="pull-right"><span data-bind="text: review.created_at"></span>&nbsp;&nbsp;<i class="icon-calendar2"></i></div>
                                                </h6>
                                            </div>
                                            <div class="panel-body">
                                                <h4 data-bind="html: review.title"></h4>
                                                <div class="reviews-item-text" data-bind="html: review.text"></div>
                                                <div class="col-lg-6 reviews-item-advantage" data-bind="html: review.advantage"></div>
                                                <div class="col-lg-6 reviews-item-disadvantages" data-bind="html: review.disadvantages"></div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <? if (Yii::$app->user->isGuest) { ?>
                                    <div class="alert alert-warning"><a href="/login/">Войдите</a>, чтобы оставить свой отзыв</div>
                                <? } else { ?>
                                    <form class="form-horizontal" role="form" id="form-reviews">
                                        <div class="panel panel-flat">
                                            <div class="panel-heading">
                                                <h5>Оставить отзыв</h5>
                                            </div>
                                            <div class="panel-body">
                                                <div class="form-group">
                                                    <input type="text" name="title" class="form-control" placeholder="Заголовок">
                                                </div>
                                                <div class="form-group">
                                                    <textarea class="form-control" name="text" placeholder="Отзыв"></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <!--label><i class="icon-thumbs-up2 text-danger"></i>&nbsp;Достоинства</label-->
                                                    <textarea class="form-control" name="advantage" placeholder="Достоинства"></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <!--label><i class="icon-thumbs-down2 text-success"></i>&nbsp;Недостатки</label-->
                                                    <textarea class="form-control" name="disadvantage" placeholder="Недостатки"></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-lg-6">
                                                        <input type="hidden" name="rating">
                                                        <label>Оцените товар:</label>

                                                        <div class="star-review" data-rtl="false">
                                                            <i class="glyphicon glyphicon-star-empty"></i>
                                                            <i class="glyphicon glyphicon-star-empty"></i>
                                                            <i class="glyphicon glyphicon-star-empty"></i>
                                                            <i class="glyphicon glyphicon-star-empty"></i>
                                                            <i class="glyphicon glyphicon-star-empty"></i>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 text-right">
                                                        <button type="button" class="btn btn-primary">Отправить</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="alert alert-success" id="reviews-msg-success">Спасибо за отзыв, он будет опубликован после проверки администратором сайта.</div>
                                <? } ?>

                        </div>
                    </div>

                </div>
            </div>

            <div class="row reorder-sm mt-20">
                <div class="col-lg-4 col-md-4 col-sm-12">
                </div>
                <div class="col-lg-8 col-md-8 col-sm-12">
                </div>
            </div>
        </div>
    </div>
</div>