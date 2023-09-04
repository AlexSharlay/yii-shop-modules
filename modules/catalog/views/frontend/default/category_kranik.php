<?php

use yii\helpers\Html;
use common\modules\shop\models\frontend\Cart;

$bundle = \frontend\themes\shop\pageAssets\catalog\category::register($this);
\common\modules\mods\mods_seo\components\seo::setMeta([
    'seo_title' => $page['seo_title'],
    'seo_desc' => $page['seo_desc']
]);

?>


<script type="text/javascript">
    function reloadPage() {
        window.location.reload()
    }
</script>

<? /* simplified_view - Упрощённый вид. Список. Только название и цена в линию */ ?>
<script>
    window.initialSchemaState = {
        schema: "<?=$category ?>&group=1",
        simplified_view: false,
        query_string: <?=json_encode($fields = (is_array(Yii::$app->request->get())) ? Yii::$app->request->get() : []) ?>,
        mfr: "<?=$manufacturer ?>",
        group: 1,
        collection: <?= (mb_substr($_SERVER['REQUEST_URI'], 0, 13) == '/collections/') ? 'true' : 'false' ?>
    };
</script>


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


    <a href="#x" class="overlay" id="filter_modal_css"></a>
    <div class="popup popup_filter">

        <!-- ---------------------------------------------------------- Фильтры ------------------------------------>
        <div class="side_info_boo">
            <aside class="brand_list_warp">

                <div class="head_rubric">
                    <span class="rubric sec_akcent alpha display-block"><i></i>Подобрать</span>
                </div>

                <div id="adv_search_block" class="adv_search_block">

                    <div class="catalog-filter__wrapper">
                        <div class="catalog-filter" id="catalog-filter" style="display: none;" data-bind="visible: true">
                            <div data-bind="template: {name: 'catalog-filter-template__facets-list', data: $root.facets.general}"></div>
                            <!-- ko if: $root.facets.additional && $root.facets.additional.length -->
                            <div class="catalog-filter-additional__wrapper">
                                <div class="catalog-filter-additional__trigger">
                                    <a data-bind="click: $root.toggleAdditionalParameters.bind($root)">Дополнительные параметры</a>
                                </div>
                                <div class="catalog-filter-additional"
                                     data-bind="css: {'catalog-filter-additional_visible': $root.isAdditionalParametersVisible}">
                                    <div data-bind="template: {name: 'catalog-filter-template__facets-list', data: $root.facets.additional}"></div>
                                </div>
                            </div>
                            <!-- /ko -->
                        </div>


                        <div class="catalog-filter__block">
                            <div style="text-align: center;"></div>
                        </div>

                    </div>

                    <script type="text/html" id="catalog-filter-template__facets-list">
                        <!-- ko foreach: {data: $data, as: 'facet'} -->
                        <div class="catalog-filter__fieldset"
                             data-bind="css: {'catalog-filter__fieldset_boolean-checkbox': facet.boolType === 'checkbox'}">

                            <!-- ko if: facet.description -->
                            <!-- ko template: {name: 'catalog-filter-template__help', data: facet} -->
                            <!-- /ko -->
                            <!-- /ko -->

                            <!-- ko if: facet.type !== 'boolean' || facet.boolType !== 'checkbox' -->
                            <div class="catalog-filter__label adv_search_razdel">
                                <span data-bind="html: facet.name + (facet.unit ? ', ' + facet.unit : '')"></span>
                            </div>
                            <!-- /ko -->

                            <!-- ko if: facet.type === 'dictionary' -->
                            <div class="catalog-filter__facet" data-bind="template: {name: 'catalog-filter-template__dictionary', data: facet}"></div>
                            <!-- /ko -->

                            <!-- ko if: facet.type === 'dictionary_range' -->
                            <div class="catalog-filter__facet" data-bind="template: {name: 'catalog-filter-template__dictionary-range', data: facet}"></div>
                            <!-- /ko -->

                            <!-- ko if: facet.type === 'number_range' -->
                            <div class="catalog-filter__facet" data-bind="template: {name: 'catalog-filter-template__number-range', data: facet}"></div>
                            <!-- /ko -->

                            <!-- ko if: facet.type === 'boolean' -->
                            <div class="catalog-filter__facet" data-bind="template: {name: 'catalog-filter-template__boolean', data: facet}"></div>
                            <!-- /ko -->

                        </div>
                        <!-- /ko -->
                    </script>

                    <script type="text/html" id="catalog-filter-template__help">
                        <div class="catalog-filter-help" data-bind="css: {'catalog-filter-help_opened': facet.isHelpPopoverOpened}">
                            <div class="catalog-filter-help__trigger" data-bind="click: facet.toggleHelpPopover.bind(facet)"></div>
                            <div class="catalog-filter-help__popover"
                                 data-bind="click: function (root, event) {event.originalEvent.stopPropagation(); return true;}">
                                <div class="catalog-filter-help__inner">
                                    <div class="catalog-filter-help__title" data-bind="html: facet.name"></div>
                                    <div data-bind="html: facet.description"></div>
                                </div>
                            </div>
                        </div>
                    </script>

                    <script type="text/html" id="catalog-filter-template__dictionary">
                        <ul class="catalog-filter__list">
                            <!-- ko foreach: {data: facet.popular.list, as: 'item'} -->
                            <li>
                                <label class="catalog-filter__checkbox-item"
                                       data-bind="css: {'catalog-filter__checkbox-item_disabled': facet.isDisabledLabel(item.id)}">
                        <span class="i-checkbox">
                            <input type="checkbox" class="i-checkbox__real" data-bind="value: item.id, checked: facet.values">
                            <span class="i-checkbox__faux"></span>
                        </span>
                                    <span class="catalog-filter__checkbox-text" data-bind="html: item.name"></span>
                                </label>
                            </li>
                            <!-- /ko -->
                        </ul>
                        <!-- ko if: facet.popular.list().length < facet.dictionary.list.length  -->
                        <div class="catalog-filter-control catalog-filter-control_more" data-bind="click: facet.togglePopover.bind(facet)">
                            <div class="catalog-filter-control__item">Все
                                <span data-bind="text: facet.dictionary.count"></span>
                                <span data-bind="text: $root.format.pluralForm(facet.dictionary.count, ['вариант', 'варианта', 'вариантов'])"></span>
                            </div>
                        </div>
                        <div class="catalog-filter-popover__wrapper">
                            <div class="catalog-filter-popover"
                                 data-bind="css: {'catalog-filter-popover_visible': facet.isPopoverOpened}, click: function (root, event) {event.originalEvent.stopPropagation(); return true;}">
                                <div class="catalog-filter-popover__inner">
                                    <div class="catalog-filter-popover__columns"
                                         data-bind="css: {'catalog-filter-popover__columns_2': facet.dictionary.count <= 50, 'catalog-filter-popover__columns_3': facet.dictionary.count > 50 && facet.dictionary.count <= 100, 'catalog-filter-popover__columns_4': facet.dictionary.count > 100}">
                                        <!-- ko foreach: {data: facet.dictionary.list, as: 'item'} -->
                                        <div class="catalog-filter-popover__column-item">
                                            <!-- ko if: item.letter -->
                                            <div class="catalog-filter-popover__column-letter" data-bind="text: item.letter"></div>
                                            <!-- /ko -->
                                            <label class="catalog-filter__checkbox-item"
                                                   data-bind="css: {'catalog-filter__checkbox-item_disabled': facet.isDisabledLabel(item.id)}">
                                    <span class="i-checkbox">
                                        <input type="checkbox" class="i-checkbox__real" data-bind="value: item.id, checked: facet.values">
                                        <span class="i-checkbox__faux"></span>
                                    </span>
                                                <span class="catalog-filter__checkbox-text" data-bind="html: item.name"></span>
                                            </label>
                                        </div>
                                        <!-- /ko -->
                                    </div>
                                </div>

                            </div>
                        </div>
                        <!-- /ko -->
                    </script>

                    <script type="text/html" id="catalog-filter-template__dictionary-range">
                        <!-- ko if: facet.predefinedRanges -->
                        <ul class="catalog-filter__list">
                            <!-- ko foreach: {data: facet.predefinedRanges, as: 'range'} -->
                            <li>
                                <label class="catalog-filter__checkbox-item">
                        <span class="i-checkbox">
                            <input type="checkbox" class="i-checkbox__real"
                                   data-bind="attr: {name: 'filter-number-range__' + facet.parameterId}, checked: facet.predefinedIndexes.from() !== undefined && facet.predefinedIndexes.to() !== undefined && $index() >= facet.predefinedIndexes.from() && $index() <= facet.predefinedIndexes.to(), event: {change: facet.changePredefinedIndexes.bind(facet, $index(), $element)}">
                            <span class="i-checkbox__faux"></span>
                        </span>
                                    <span class="catalog-filter__checkbox-text" data-bind="html: range.name"></span>
                                </label>
                            </li>
                            <!-- /ko -->
                        </ul>
                        <!-- /ko -->
                        <div class="catalog-filter__group">
                            <div class="catalog-filter-control catalog-filter-control_select">
                                <select class="catalog-filter-control__item"
                                        data-bind="options: facet.dictionary.list, optionsText: 'name', optionsValue: 'id', value: facet.value.from, optionsCaption: ''"></select>
                            </div>
                            <div class="catalog-filter-control catalog-filter-control_select">
                                <select class="catalog-filter-control__item"
                                        data-bind="options: facet.dictionary.list, optionsText: 'name', optionsValue: 'id', value: facet.value.to, optionsCaption: ''"></select>
                            </div>
                        </div>
                    </script>

                    <script type="text/html" id="catalog-filter-template__number-range">
                        <!-- ko if: facet.predefinedRanges -->
                        <ul class="catalog-filter__list">
                            <!-- ko foreach: {data: facet.predefinedRanges, as: 'range'} -->
                            <li>
                                <label class="catalog-filter__checkbox-item">
                        <span class="i-checkbox">
                            <input type="checkbox" class="i-checkbox__real"
                                   data-bind="attr: {name: 'filter-number-range__' + facet.parameterId}, checked: facet.predefinedIndexes.from() !== undefined && facet.predefinedIndexes.to() !== undefined && $index() >= facet.predefinedIndexes.from() && $index() <= facet.predefinedIndexes.to(), event: {change: facet.changePredefinedIndexes.bind(facet, $index(), $element)}">
                            <span class="i-checkbox__faux"></span>
                        </span>
                                    <span class="catalog-filter__checkbox-text" data-bind="html: range.name"></span>
                                </label>
                            </li>
                            <!-- /ko -->
                        </ul>
                        <!-- /ko -->

                        <div class="catalog-filter__group">
                            <div class="catalog-filter-control catalog-filter-control_input">
                                <input class="catalog-filter-control__item catalog-filter__number-input" type="text"
                                       data-bind="value: facet.value.from, attr: {placeholder: !facet.value.to() && (facet.placeholder.from || 'от')}, click: facet.onClick.bind(facet), css: {'catalog-filter__number-input_price': facet.parameterId === 'price'}, valueUpdate: 'keyup'">
                                <span class="catalog-filter-control__shadow"></span>
                            </div>
                            <div class="catalog-filter-control catalog-filter-control_input">
                                <input class="catalog-filter-control__item catalog-filter__number-input" type="text"
                                       data-bind="value: facet.value.to, attr: {placeholder: !facet.value.from() && (facet.placeholder.to || 'до')}, click: facet.onClick.bind(facet), css: {'catalog-filter__number-input_price': facet.parameterId === 'price'}, valueUpdate: 'keyup'">
                                <span class="catalog-filter-control__shadow"></span>
                            </div>
                        </div>

                    </script>

                    <script type="text/html" id="catalog-filter-template__boolean">

                        <!-- ko if: facet.boolType === 'checkbox' -->
                        <label class="catalog-filter__checkbox-item">
                        <span class="i-checkbox">
                            <input type="checkbox" class="i-checkbox__real"
                                   data-bind="checked: $data.value() === '1', event: {change: $data.change.bind($data, '1', $element)}">
                            <span class="i-checkbox__faux"></span>
                        </span>
                            <span class="catalog-filter__checkbox-text" data-bind="html: facet.name + (facet.unit ? ', ' + facet.unit : '')"></span>
                        </label>
                        <!-- /ko -->

                        <!-- ko if: facet.boolType === 'yesno' -->
                        <div class="catalog-filter__group">
                            <label class="catalog-filter-control catalog-filter-control_switcher">
                                <input class="catalog-filter-control__switcher-state" type="checkbox"
                                       data-bind="checked: $data.value() === '1', event: {change: $data.change.bind($data, '1', $element)}">
                                <span class="catalog-filter-control__switcher-inner">Да</span>
                            </label>

                            <label class="catalog-filter-control catalog-filter-control_switcher">
                                <input class="catalog-filter-control__switcher-state" type="checkbox"
                                       data-bind="checked: $data.value() === '0', event: {change: $data.change.bind($data, '0', $element)}">
                                <span class="catalog-filter-control__switcher-inner">Нет</span>
                            </label>
                        </div>
                        <!-- /ko -->

                    </script>
                </div>


                <div class="catalog-filter-button" id="catalog-filter-button" style="display: none;"
                     data-bind="css: {'catalog-filter-button_fixed': $root.states.fixed,'catalog-filter-button_hanged': $root.states.hanged}, visible: true">
                    <div class="catalog-filter-button__inner-container">
                        <div class="catalog-filter-button__inner"
                             data-bind="css: {'catalog-filter-button__inner_moved': $root.states.moved}, event: {mouseout: $root.onMouseout.bind($root), mouseover: $root.onMouseover.bind($root)}">
                            <div class="catalog-filter-button__state catalog-filter-button__state_initial catalog-filter-button__state_disabled"
                                 data-bind="css: {'catalog-filter-button__state_control': $root.states.control, 'catalog-filter-button__state_animated': $root.states.animated}">
                                <span class="catalog-filter-button__sub catalog-filter-button__sub_control" data-bind="click: $root.doMoved.bind($root)"></span>
                                <span class="catalog-filter-button__sub catalog-filter-button__sub_main"
                                      data-bind="html: $root.text, click: $root.apply.bind($root)"></span>
                            </div>
                            <div class="catalog-filter-button__state catalog-filter-button__state_clear catalog-filter-button__state_hidden">
                                <span class="catalog-filter-button__sub" data-bind="click: $root.clear.bind($root)">Сбросить фильтр</span>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="adv_search_btn">
                    <?= Html::button('Показать товары', ['id' => 'search_btn', 'style' => 'position:relative;', 'class' => 'btn smb', 'onclick' => 'reloadPage()']) ?>
                </div>

            </aside>
        </div>
        <!-- ---------------------------------------------------------- /Фильтры ------------------------------------>
        <a class="close_filter" title="Закрыть" href="#close"></a>
    </div>


    <div class="main_content_boo">

        <div class="warp">

            <h1 class="rubric akcent alpha page_h1">
                <span><?= $page['title'] ?></span>
            </h1>
            <br/>

            <?php if (!empty($categoriesForMenuImg)): ?>
                <div class="cat_grid">
                    <ul class="cat_grid">
                        <?php foreach ($categoriesForMenuImg as $item): ?>
                            <li class="cat_item">
                                <a href="<?= $item['url'] ?>">
                                    <?php if ($item['ico']): ?>
                                        <img class="category_img" src="<?= $item['ico'] ?>" alt=""
                                             style="max-height: 88px; max-width: 88px;"/>
                                    <?php endif; ?>
                                    <span class="category_pselink"><span><?= $item['title'] ?></span></span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="clearfix"></div>
            <?php endif; ?>


            <p style="padding-top: 15px;color: #000000;">
                <?= $page['desc_top'] ?>
            </p>
            <div class="row" style="padding:10px 0 0 0;display:block;">
                <?= $page['desc_filter'] ?>
                <style>
                    table{width:100%;border-collapse:collapse;}
                    th{padding:5px 20px;border:0 solid #000;font-weight:bold;color:#000;}
                    td{padding:2px 10px;border:0 solid #000;}
                </style>
            </div>
            <?php
            //<a href="' . \yii\helpers\Url::to(['/category/default/filter-modal']) . '" class="cart_smb btn smb filter-modal">Фильтр</a>
            //<a href="#" onclick="return getFilterModal()"><i style="float:left; padding-right: 20px;" class="fa fa-filter fa_icon" aria-hidden="true"></i></a>
            //<a href="#filter_modal_css"><i style="background-color: #E31E25; color: #fff" class="fa fa-filter fa_icon filter_button" aria-hidden="true"></i></a>
            ?>
            <a href="#filter_modal_css"><img src="/statics/web/site/files/sliders_icon.png" class="fa_icon filter_button"
                                             style="width:46px;opacity:0.9;"/></a>
            <div class="selector_view_product">
                <div class="toolbar selector_view_qnt">
                    <p class="amount"><span style="color: #c21310"><?= $categoryProducts['total'] ?></span>
                        <?= numberof($categoryProducts['total'], 'товар', array('', 'а', 'ов')) ?></p>
                    <div class="selector show_qnt">
                        <div class="catalog-order" id="catalog-order" style="display:none;"
                             data-bind="visible: true, css: {'catalog-order_opened': $root.isOpened}">
                            <a id="catalog_sort" class="catalog-order__link" data-bind="click: $root.toggle">
                                Сначала <span class="catalog-order__text" data-bind="text: $root.active().text"></span>
                            </a>
                            <div class="catalog-order__popover">
                                <div class="catalog-order__list" data-bind="foreach: {data: $root.types, as: 'type'}">
                                    <div class="catalog-order__item"
                                         data-bind="css: {'catalog-order__item_active': $root.active().type === type}, click: function () { $root.change(type) }">
                                        <span data-bind="text: $root.items[type].text"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div>

                <div class="catalog-tags" id="catalog-tags" style="display:none;" data-bind="visible: true">
                    <!-- ko foreach: {data: $root.tags, as: 'tag'} -->
                    <div class="catalog-tags__item"
                         data-bind="click: $root.removeTag.bind($root, tag), attr: {title: tag.facet.name}">
                        <span class="catalog-tags__text" data-bind="html: tag.text"></span>
                    </div>
                    <!-- /ko -->
                </div>

                <div class="catalog-products" id="catalog-products" style="display:none;"
                     data-bind="visible: true, css: {'catalog-products_simplified': $root.isSimplifiedView, 'catalog-products_processing': $root.isProcessing()}">

                    <ul class="cat_product_grid">
                        <?php if (!empty($categoryProducts['products'])): ?>
                            <?php foreach ($categoryProducts['products'] as $product): ?>
                                <li class="cat_product_item">
                                    <div id="div_<?= $product['id'] ?>" style="height:200px;width:223px;position:relative;margin:0 auto;">
                                        <a href="<?= $product['url'] ?>">
                                            <img id="img_<?= $product['id'] ?>" class="product_img" src="<?= $product['images']['header'] ?>" alt=""/>
                                        </a>
                                    </div>

                                    <?php if ($product['in_stock'] > 0): ?>
                                        <?php if ((($product['halva'] != 0) && empty($product['prices']['old'])) && !empty($product['prices']['min']) && empty($product['perekup_manufacturer'])) : ?>
                                            <?php //if (($product['halva'] != 0) && !empty($product['prices']['min']) && empty($product['perekup_manufacturer'])) : ?>
                                            <a href="/oplata/" target="_blank">
                                                <?= Html::img('@web/statics/catalog/media/images/halva.png', ['alt' => 'Карта Халва', 'style' => 'position:absolute; height: 18px; bottom: 52px; right: 10px']) ?>
                                                <?= Html::img('@web/statics/catalog/media/images/card_turtle.png', ['alt' => 'Карта Черепаха', 'style' => 'position:absolute; height: 18px; bottom: 52px; right: 41px']) ?>
                                                <?= Html::img('@web/statics/web/site/files/karta_pokupok.png', ['alt' => 'Карта покупок', 'style' => 'position:absolute; height: 18px; bottom: 52px; right: 72px']) ?>
                                                <?= Html::img('@web/statics/web/site/files/smart_karta.png', ['alt' => 'Smart карта', 'style' => 'position:absolute; height: 18px; bottom: 52px; right: 103px']) ?>
                                                <?= Html::img('@web/statics/web/site/files/mocna_kartka.jpg', ['alt' => 'Моцная картка', 'style' => 'position:absolute; height: 18px; bottom: 52px; right: 134px']) ?>
                                                <?= Html::img('@web/statics/web/site/files/prior.png', ['alt' => 'Приорбанк-рассрочка', 'style' => 'position:absolute; height: 18px; bottom: 52px; right: 165px']) ?>
                                                <?= Html::img('@web/statics/web/site/files/magnit.png', ['alt' => 'Магнит', 'style' => 'position:absolute; height: 18px; bottom: 52px; right: 196px']) ?>
                                            </a>
                                        <?php endif; ?>
                                        <?php if ($product['in_status'] == 3): ?>
                                            <?= Html::img('@web/statics/catalog/media/images/akcia_exposicia.png', ['alt' => 'Товар с экспозиции', 'class' => 'element-expositions']) ?>
                                        <?php endif; ?>
                                        <?php if ($product['in_action'] == 1): ?>
                                            <?= Html::img('@web/statics/catalog/media/images/akcia.png', ['alt' => 'Акционный товар', 'class' => 'element-action']) ?>
                                        <?php endif; ?>
                                        <?php if ($product['title_manufacturer'] == 'Grohe'): ?>
                                            <a target="_blank" href="/news/bolshe-pokupok-produkcii-grohe---bolshe-stilnyh-i-brendovyh-podarkov/">
                                                <?= Html::img('@web/statics/site/files/podarok.png', ['alt' => 'Подарок', 'style' => 'position:absolute; height: 50px; bottom: 72px; right: 10px']) ?>
                                            </a>
                                        <?php endif; ?>
                                        <?php if (($product['title_manufacturer'] == 'JIKA') && ($page['title'] == 'Унитазы подвесные' || 'Унитазы напольные (компакт)')): ?>
                                            <a target="_blank" href="/news/podarok-za-pokupku-ot-proizvoditelya-jika/">
                                                <?= Html::img('@web/statics/site/files/podarok.png', ['alt' => 'Подарок', 'style' => 'position:absolute; height: 50px; bottom: 72px; right: 10px']) ?>
                                            </a>
                                        <?php endif; ?>

                                    <?php endif; ?>

                                    <div class="product_info">
                                        <p class="product_name">
                                            <a href="<?= $product['url'] ?>"><?= $product['full_name'] ?></a>
                                        </p>
                                        <p class="product_manufact">
                                            <img style="top:3px;position:relative;" src="<?= $product['ico_country'] ?>" alt="">
                                            <a href="/brand/<?= $product['alias_manufacturer'] ?>/"><?= $product['title_manufacturer'] ?></a>
                                            <?= $product['title_country'] ?>
                                        </p>
                                        <?php if ($product['article']): ?>
                                            <p style="padding:5px 0">Код товара: <span style="color:#000"><?= $product['article'] ?></span></p>
                                        <?php endif; ?>


                                        <div>
                                            <?php if (($product['in_stock'] > 0) OR (isset($product['children']))): ?>
                                                <?php if (isset($product['children'])): ?>
                                                    <div style="position:absolute;left:10px;bottom:10px">
                                                        <p class="price new_price">
                                                            <span style="font-size:15px;">
                                                                <?= ($product['prices']['min'])
                                                                    ? $product['prices']['after'] . ' ' . price($product['prices']['min'])
                                                                    : '<p class="price new_price">Скоро на складе</p>' ?>
                                                            </span>
                                                        </p>
                                                    </div>
                                                    <span style="position:absolute;right:10px;bottom:10px;color:#000;">
                                                            <a href="<?= $product['url'] ?>">
                                                                Показать<br/><?= count($product['children']) . ' ' .
                                                                numberof(count($product['children']), 'модел', array('ь', 'и', 'ей')) ?>
                                                            </a>
                                                    </span>
                                                <?php else: ?>
                                                    <div style="position:absolute;left:10px;bottom:10px;">
                                                        <p class="price new_price">
                                                            <span style="font-size:15px;"><?= price($product['prices']['min']) ?></span>
                                                        </p>
                                                        <?php if ($product['prices']['old'] > $product['prices']['min']): ?>
                                                            <p class="price old_price">
                                                                <span style="font-size:14px;"><?= price($product['prices']['old'], false) ?></span>
                                                            </p>
                                                        <?php endif; ?>
                                                    </div>





                                                    <button type="button" class="btn btn-sm ml-5"
                                                        <? if (Yii::$app->user->isGuest) { ?>
                                                            onclick="window.location.href = '/signup/'"
                                                        <? } else { ?>
                                                            data-bind="attr: {id: 'btn-cart-'+$data.id}, click: function() { vmCart.addProductFromCategory($data.id); }"
                                                        <? } ?>
                                                    >В корзину
                                                    </button>







                                                    <span style="position:absolute;right:0;bottom:10px;">
                                                        <a href="<?= \yii\helpers\Url::to(['/shop/cart/add', 'id' => $product['id']]) ?>"
                                                           data-id="<?= $product['id'] ?>" class="add-to-cart cart_smb btn smb">
                                                           <i class="fa fa-shopping-cart"></i>В корзину
                                                        </a>
                                                    </span>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <div style="position:absolute;left:10px;bottom:10px;">
                                                    <p class="price">Последняя цена: <?= price($product['prices']['min']) ?></p>
                                                    <p class="price new_price">Скоро на складе</p>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                    </div>
                                </li>

                            <?php endforeach; ?>
                            <div class="clearfix"></div>
                        <?php else : ?>
                            <!--<h2>Здесь товаров пока нет...</h2>-->
                        <?php endif; ?>
                    </ul>

                </div>
                <div class="clearfix"></div>

<!--                <script type="text/html" id="catalog-product__price-template">-->
<!--                    <!-- ko if: $data.status === 'active' -->-->
<!---->
<!--                    <!-- ko if: $data.prices -->-->
<!--                    <div class="catalog-product__price">-->
<!--                        <a data-bind="attr: {href: $data.url}">-->
<!--                            <span data-bind="html: $data.prices.after"></span>-->
<!--                            <span data-bind="text: formatCurrency($data.prices.min)"></span>-->
<!--                        </a>-->
<!--                        <!-- ko if: !$data.prices.after -->-->
<!--                        <br/>-->
<!--                        <br/>-->
<!--                        <button type="button" class="btn btn-sm ml-5"-->
<!--                            --><?// if (Yii::$app->user->isGuest) { ?>
<!--                                onclick="window.location.href = '/signup/'"-->
<!--                            --><?// } else { ?>
<!--                                data-bind="attr: {id: 'btn-cart-'+$data.id}, click: function() { vmCart.addProductFromCategory($data.id); }"-->
<!--                            --><?// } ?>
<!--                        >В корзину-->
<!--                        </button>-->
<!--                        <input class="form-control product_in_stock" type="number" min="1" name="number" value="1" data-bind="attr: {id: 'count-'+$data.id}">-->
<!--                        <!-- /ko -->-->
<!--                    </div>-->
<!--                    <!-- /ko -->-->
<!---->
<!---->
<!--                    <!-- ko if: !$data.prices -->-->
<!--                    <div class="catalog-product__status">Нет в наличии</div>-->
<!--                    <!-- /ko -->-->
<!---->
<!--                    <!-- /ko -->-->
<!--                </script>-->

                <script type="text/html" id="catalog-product__price-min-template">

                    <!-- ko if: $data.status === 'active' -->

                    <!-- ko if: $data.prices -->
                    <div class="catalog-product__price">
                        <a data-bind="attr: {href: $data.url}">
                            <span data-bind="html: $data.prices.after"></span>
                            <span data-bind="text: formatCurrency($data.prices.min)"></span>
                        </a>
                        <input class="form-control product_in_stock" type="number" min="1" name="number" value="1"
                               data-bind="attr: {id: 'count-'+$data.id}">
                        <br/>
                        <button type="button" class="btn btn-sm ml-5 pull-right mt-10"
                                data-bind="attr: {id: 'btn-cart-'+$data.id}, click: function() { vmCart.addProductFromCategory($data.id); }">
                            В корзину
                        </button>
                    </div>
                    <!-- /ko -->

                    <!-- ko if: !$data.prices -->
                    <div class="catalog-product__status">Нет в наличии</div>
                    <!-- /ko -->

                    <!-- /ko -->
                </script>

                <script type="text/html" id="catalog-product__children-template">
                    <div class="catalog-product catalog-product_children">
                        <div class="catalog-product__part catalog-product__part_2">
                            <div class="catalog-product__part catalog-product__part_3">
                                <div data-bind="template: {name: 'catalog-product__price-min-template', data: children}"></div>
                            </div>
                            <div class="catalog-product__part catalog-product__part_4">
                                <div class="catalog-product__description">
                                    <a data-bind="attr: {href: children.url}">
                                        <span data-bind="html: children.micro_description || children.description"></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </script>

                <script type="text/html" id="catalog-special-block">
                    <div class="catalog-special-block"></div>
                </script>

                <div class="catalog-pagination" id="catalog-pagination" style="display:none;"
                     data-bind="visible: $root.page.last() > 1, css: {'catalog-pagination_visible': $root.isVisible()}">
                    <a class="catalog-pagination__main"
                       data-bind="click: $root.nextPage.bind($root),css: {'catalog-pagination__main_disabled': $root.page.current() == $root.page.last()}">
                        <span data-bind="text: $root.nextPageText"></span>
                    </a>

                    <div class="catalog-pagination__pages" data-bind="css: {'catalog-pagination__pages_active': $root.isActiveDropdown}">
                        <div class="catalog-pagination__pages-container">
                            <ul class="catalog-pagination__pages-list" data-bind="foreach: {data: $root.pages, as: 'number'}">
                                <li class="catalog-pagination__pages-item"
                                    data-bind="css: {'catalog-pagination__pages-item_active': $root.page.current() === number}">
                                    <a class="catalog-pagination__pages-link" data-bind="click: function () { $root.setPage(number) }, text: number"></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <br/>

                <?= \common\modules\catalog\components\category\MyPager::widget([
                    'pagination' => $pages,
                ])
                ?>

                <script type="text/javascript">
                    /**
                     * Функция Скрывает/Показывает блок
                     * @author ox2.ru дизайн студия
                     **/
                    function showHide(element_id) {
                        //Если элемент с id-шником element_id существует
                        if (document.getElementById(element_id)) {
                            //Записываем ссылку на элемент в переменную obj
                            var obj = document.getElementById(element_id);
                            //Если css-свойство display не block, то:
                            if (obj.style.display != "block") {
                                obj.style.display = "block"; //Показываем элемент
                            }
                            else obj.style.display = "none"; //Скрываем элемент
                        }
                        //Если элемент с id-шником element_id не найден, то выводим сообщение
                        else alert("Элемент с id: " + element_id + " не найден!");
                    }
                </script>

                <div class="warp">
                    <article class="info_txt" style="color:#000;">
                        <?= $page['desc_bottom'] ?>
                    </article>
                </div>

                <?php if ($page['desc_filter_bottom'] <> ''): ?>
                    <div class="toolbar selector_view_qnt desc_filter_bottom" style="margin-top:10px;">
                        <p style="padding-bottom:5px;color:#000;font-weight: bold">Популярное в категории:</p>
                        <?= $page['desc_filter_bottom'] ?>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>

</div>
