<?php
$bundle = \frontend\themes\shop\pageAssets\catalog\category::register($this);
\common\modules\mods\mods_seo\components\seo::setMeta([
    'seo_title' => $page['seo_title'],
    'seo_keyword' => $page['seo_keyword'],
    'seo_desc' => $page['seo_desc']
]);
?>

<? /* simplified_view - Упрощённый вид. Список. Только название и цена в линию */ ?>
<script>
    window.initialSchemaState = {
        schema: "<?=$category;?>&group=1",
        simplified_view: false,
        query_string: <?=json_encode($fields = (is_array(Yii::$app->request->get())) ? Yii::$app->request->get() : []);?>,
        mfr: "<?=$manufacturer;?>",
        group: 1,
        collection: <? echo (mb_substr($_SERVER['REQUEST_URI'], 0, 13) == '/collections/') ? 'true' : 'false'; ?>
    };
</script>

<div class="catalog-content js-scrolling-area">
    <div class="catalog-grid__wrapper">

        <div id="catalog-scroll-to"></div>

        <div class="catalog-header">
            <div class="catalog-order" id="catalog-order" style="display: none;" data-bind="visible: true, css: {'catalog-order_opened': $root.isOpened}">
                <a class="catalog-order__link" data-bind="click: $root.toggle">
                    Сначала <span class="catalog-order__text" data-bind="text: $root.active().text"></span>
                </a>
                <div class="catalog-order__popover">
                    <div class="catalog-order__list" data-bind="foreach: {data: $root.types, as: 'type'}">
                        <div class="catalog-order__item" data-bind="css: {'catalog-order__item_active': $root.active().type === type}, click: function () { $root.change(type) }">
                            <span data-bind="text: $root.items[type].text"></span>
                        </div>
                    </div>
                </div>
            </div>
            <h1 class="catalog-header__title"><?=$page['title'];?></h1>
        </div>

        <div class="catalog-grid">

            <div class="catalog-grid__left-column">

                <div class="catalog-filter__wrapper">
                    <div class="catalog-filter" id="catalog-filter" style="display: none;" data-bind="visible: true">
                        <div data-bind="template: {name: 'catalog-filter-template__facets-list', data: $root.facets.general}"></div>
                        <!-- ko if: $root.facets.additional && $root.facets.additional.length -->
                        <div class="catalog-filter-additional__wrapper">
                            <div class="catalog-filter-additional__trigger">
                                <a data-bind="click: $root.toggleAdditionalParameters.bind($root)">Дополнительные параметры</a>
                            </div>
                            <div class="catalog-filter-additional" data-bind="css: {'catalog-filter-additional_visible': $root.isAdditionalParametersVisible}">
                                <div data-bind="template: {name: 'catalog-filter-template__facets-list', data: $root.facets.additional}"></div>
                            </div>
                        </div>
                        <!-- /ko -->
                    </div>

                    <div class="catalog-filter-button" id="catalog-filter-button" style="display: none;" data-bind="css: {'catalog-filter-button_fixed': $root.states.fixed,'catalog-filter-button_hanged': $root.states.hanged}, visible: true">
                        <div class="catalog-filter-button__inner-container">
                            <div class="catalog-filter-button__inner" data-bind="css: {'catalog-filter-button__inner_moved': $root.states.moved}, event: {mouseout: $root.onMouseout.bind($root), mouseover: $root.onMouseover.bind($root)}">
                                <div class="catalog-filter-button__state catalog-filter-button__state_initial catalog-filter-button__state_disabled" data-bind="css: {'catalog-filter-button__state_control': $root.states.control, 'catalog-filter-button__state_animated': $root.states.animated}">
                                    <span class="catalog-filter-button__sub catalog-filter-button__sub_control" data-bind="click: $root.doMoved.bind($root)"></span>
                                    <span class="catalog-filter-button__sub catalog-filter-button__sub_main" data-bind="html: $root.text, click: $root.apply.bind($root)"></span>
                                </div>
                                <div class="catalog-filter-button__state catalog-filter-button__state_clear catalog-filter-button__state_hidden">
                                    <span class="catalog-filter-button__sub" data-bind="click: $root.clear.bind($root)">Сбросить фильтр</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="catalog-filter__block">
                        <div style="text-align: center;"></div>
                    </div>

                </div>

                <script type="text/html" id="catalog-filter-template__facets-list">
                    <!-- ko foreach: {data: $data, as: 'facet'} -->
                    <div class="catalog-filter__fieldset" data-bind="css: {'catalog-filter__fieldset_boolean-checkbox': facet.boolType === 'checkbox'}">

                        <!-- ko if: facet.description -->
                        <!-- ko template: {name: 'catalog-filter-template__help', data: facet} -->
                        <!-- /ko -->
                        <!-- /ko -->

                        <!-- ko if: facet.type !== 'boolean' || facet.boolType !== 'checkbox' -->
                        <div class="catalog-filter__label">
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
                        <div class="catalog-filter-help__popover" data-bind="click: function (root, event) {event.originalEvent.stopPropagation(); return true;}">
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
                            <label class="catalog-filter__checkbox-item" data-bind="css: {'catalog-filter__checkbox-item_disabled': facet.isDisabledLabel(item.id)}">
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
                        <div class="catalog-filter-popover" data-bind="css: {'catalog-filter-popover_visible': facet.isPopoverOpened}, click: function (root, event) {event.originalEvent.stopPropagation(); return true;}">
                            <div class="catalog-filter-popover__inner">
                                <div class="catalog-filter-popover__columns" data-bind="css: {'catalog-filter-popover__columns_2': facet.dictionary.count <= 50, 'catalog-filter-popover__columns_3': facet.dictionary.count > 50 && facet.dictionary.count <= 100, 'catalog-filter-popover__columns_4': facet.dictionary.count > 100}">
                                    <!-- ko foreach: {data: facet.dictionary.list, as: 'item'} -->
                                    <div class="catalog-filter-popover__column-item">
                                        <!-- ko if: item.letter -->
                                        <div class="catalog-filter-popover__column-letter" data-bind="text: item.letter"></div>
                                        <!-- /ko -->
                                        <label class="catalog-filter__checkbox-item" data-bind="css: {'catalog-filter__checkbox-item_disabled': facet.isDisabledLabel(item.id)}">
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
                            <input type="checkbox" class="i-checkbox__real" data-bind="attr: {name: 'filter-number-range__' + facet.parameterId}, checked: facet.predefinedIndexes.from() !== undefined && facet.predefinedIndexes.to() !== undefined && $index() >= facet.predefinedIndexes.from() && $index() <= facet.predefinedIndexes.to(), event: {change: facet.changePredefinedIndexes.bind(facet, $index(), $element)}">
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
                            <select class="catalog-filter-control__item" data-bind="options: facet.dictionary.list, optionsText: 'name', optionsValue: 'id', value: facet.value.from, optionsCaption: ''"></select>
                        </div>
                        <div class="catalog-filter-control catalog-filter-control_select">
                            <select class="catalog-filter-control__item" data-bind="options: facet.dictionary.list, optionsText: 'name', optionsValue: 'id', value: facet.value.to, optionsCaption: ''"></select>
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
                            <input type="checkbox" class="i-checkbox__real" data-bind="attr: {name: 'filter-number-range__' + facet.parameterId}, checked: facet.predefinedIndexes.from() !== undefined && facet.predefinedIndexes.to() !== undefined && $index() >= facet.predefinedIndexes.from() && $index() <= facet.predefinedIndexes.to(), event: {change: facet.changePredefinedIndexes.bind(facet, $index(), $element)}">
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
                            <input class="catalog-filter-control__item catalog-filter__number-input" type="text" data-bind="value: facet.value.from, attr: {placeholder: !facet.value.to() && (facet.placeholder.from || 'от')}, click: facet.onClick.bind(facet), css: {'catalog-filter__number-input_price': facet.parameterId === 'price'}, valueUpdate: 'keyup'">
                            <span class="catalog-filter-control__shadow"></span>
                        </div>
                        <div class="catalog-filter-control catalog-filter-control_input">
                            <input class="catalog-filter-control__item catalog-filter__number-input" type="text" data-bind="value: facet.value.to, attr: {placeholder: !facet.value.from() && (facet.placeholder.to || 'до')}, click: facet.onClick.bind(facet), css: {'catalog-filter__number-input_price': facet.parameterId === 'price'}, valueUpdate: 'keyup'">
                            <span class="catalog-filter-control__shadow"></span>
                        </div>
                    </div>

                </script>
                <script type="text/html" id="catalog-filter-template__boolean">

                    <!-- ko if: facet.boolType === 'checkbox' -->
                    <label class="catalog-filter__checkbox-item">
                        <span class="i-checkbox">
                            <input type="checkbox" class="i-checkbox__real" data-bind="checked: $data.value() === '1', event: {change: $data.change.bind($data, '1', $element)}">
                            <span class="i-checkbox__faux"></span>
                        </span>
                        <span class="catalog-filter__checkbox-text" data-bind="html: facet.name + (facet.unit ? ', ' + facet.unit : '')"></span>
                    </label>
                    <!-- /ko -->

                    <!-- ko if: facet.boolType === 'yesno' -->
                    <div class="catalog-filter__group">
                        <label class="catalog-filter-control catalog-filter-control_switcher">
                            <input class="catalog-filter-control__switcher-state" type="checkbox" data-bind="checked: $data.value() === '1', event: {change: $data.change.bind($data, '1', $element)}">
                            <span class="catalog-filter-control__switcher-inner">Да</span>
                        </label>

                        <label class="catalog-filter-control catalog-filter-control_switcher">
                            <input class="catalog-filter-control__switcher-state" type="checkbox" data-bind="checked: $data.value() === '0', event: {change: $data.change.bind($data, '0', $element)}">
                            <span class="catalog-filter-control__switcher-inner">Нет</span>
                        </label>
                    </div>
                    <!-- /ko -->

                </script>

            </div>

            <div class="catalog-grid__right-column">
                <div class="catalog-menu">
                    <span class="text-bold">Категории</span>
                    <ul id="cat-tree">
                    <?php

                    if (isset($categories) && is_array($categories)) {
                        foreach ($categories as $category) {
                            //echo ($category['active']) ?  '<li class="active">' : '<li class="closed">';
                            echo '<li class="active">';
                            echo '<span>' . $category['title'] . '</span>';
                            if (isset($category['childs']) && is_array($category['childs'])) {
                                echo '<ul>';
                                foreach ($category['childs'] as $item) {
                                    $class = ($item['active']) ? 'active' : '';
                                    echo '<li><a href="'.$item['url'].'/'.$item['params'].'" class="'.$class.'">'.$item['title'].'</a></li>';
                                }
                                echo '</ul>';
                            }
                            echo '</li>';
                        }
                    }
                    ?>
                    </ul>
                </div>
            </div>

            <div class="catalog-grid__center-column">

                <div class="catalog-tags" id="catalog-tags" style="display: none;" data-bind="visible: true">

                    <!-- ko foreach: {data: $root.tags, as: 'tag'} -->
                    <div class="catalog-tags__item" data-bind="click: $root.removeTag.bind($root, tag), attr: {title: tag.facet.name}">
                        <span class="catalog-tags__text" data-bind="html: tag.text"></span>
                    </div>
                    <!-- /ko -->

                </div>

                <div class="catalog-products" id="catalog-products" style="display: none;" data-bind="visible: true, css: {'catalog-products_simplified': $root.isSimplifiedView, 'catalog-products_processing': $root.isProcessing()}">

                    <div style="right: 200px;border-right:1px solid #ddd;height:100%;position:absolute;z-index:3;"></div>

                    <!-- ko if: !$root.isError() && $root.products() && !$root.products().length -->
                    <div class="catalog-products__message">
                        Товаров удовлетворяющих условиям поиска не найдено.
                    </div>
                    <!-- /ko -->

                    <!-- ko if: $root.isError() -->
                    <div class="catalog-products__message">
                        Произошла ошибка. Пожалуйста, повторите попытку позже.
                    </div>
                    <!-- /ko -->

                    <!-- ko if: $root.products() && !$root.products().length -->
                    <!-- ko template: {name: 'catalog-special-block'} -->
                    <!-- /ko -->
                    <!-- /ko -->

                    <!-- ko foreach: {data: $root.products, as: 'product'} -->

                    <div class="catalog-product__group">
                        <div class="catalog-product" data-bind="css: {'catalog-product_narrow-sizes': $root.isNarrowImages}">

                            <!-- ko if: !$root.isSimplifiedView -->
                            <div class="catalog-product__part catalog-product__part_1">

                                <div class="catalog-product__image">
                                    <!-- ko if: product.images.header -->
                                    <a data-bind="attr: {href: product.url}">
                                        <img data-bind="attr: {src: product.images.header, alt: product.full_name, title: product.full_name},
                                        style: {'max-width': product.sizes_percents.width ? product.sizes_percents.width + '%' : '',
                                                'max-height': (product.image_size && product.image_size.height) ? Math.min(product.image_size.height, $root.imageBaseSizes.height) + 'px' : ''}">
                                    </a>
                                    <!-- /ko -->
                                </div>

                            </div>
                            <!-- /ko -->
                            <div class="catalog-product__part catalog-product__part_2">
                                <div class="catalog-product__part catalog-product__part_3">

                                    <div class="catalog-product__price-group" data-bind="template: {name: 'catalog-product__price-template', data: product}"></div>

                                </div>
                                <div class="catalog-product__part catalog-product__part_4">

                                    <div class="catalog-product__title">
                                        <a data-bind="attr: {href: product.url}">
                                            <span data-bind="text: product.full_name"></span>
                                        </a>
                                    </div>

                                    <!-- ko if: !$root.isSimplifiedView -->
                                    <div class="catalog-product__description">
                                        <span data-bind="html: product.micro_description"></span>
                                    </div>

                                    <div class="catalog-product__info">
                                        <div class="catalog-product__rating-group">
                                            <!-- ko if: product.review && product.review.count -->
                                            <span class="catalog-product__rating" >
                                                <span data-bind="attr: {class: 'rating rating_' + product.review.rating}"></span>
                                                <span class="catalog-product__review-count">
                                                    <span data-bind="text: product.review.count"></span>
                                                    <span data-bind="text: $root.format.pluralForm(product.review.count, ['отзыв', 'отзыва', 'отзывов'])"></span>
                                                </span>
                                            </span>
                                            <!-- /ko -->
                                        </div>
                                    </div>
                                    <!-- /ko -->

                                </div>
                                <div class="catalog-product__part catalog-product__part_5">
                                    <!-- ko if: !$root.isSimplifiedView && product.children && product.children.length -->
                                    <div class="catalog-product__children-group">
                                        <div class="catalog-product__more-wrapper">
                                            <!-- ko if: !product.showChildren() -->
                                            <a class="catalog-product__more-link" data-bind="click: function () {product.showChildren(true)}">
                                                Показать <span data-bind="text: product.children.length"></span>
                                                <span data-bind="text: $root.format.pluralForm(product.children.length, ['модель', 'модели', 'моделей'])"></span>
                                            </a>
                                            <!-- /ko -->

                                            <!-- ko if: product.showChildren() -->
                                            <a class="catalog-product__more-link" data-bind="click: function () {product.showChildren(false)}">
                                                Скрыть модели
                                            </a>
                                            <!-- /ko -->
                                        </div>

                                        <!-- ko if: product.showChildren -->
                                        <!-- ko template: {name: 'catalog-product__children-template', foreach: product.children, as: 'children'} -->
                                        <!-- /ko -->
                                        <!-- /ko -->
                                    </div>
                                    <!-- /ko -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ko if: ($index() === 1) || ($root.products().length === 1) -->
                    <!-- ko template: {name: 'catalog-special-block'} -->
                    <!-- /ko -->
                    <!-- /ko -->

                    <!-- /ko -->

                </div>

                <script type="text/html" id="catalog-product__price-template">

                    <!-- ko if: $data.status === 'active' -->

                    <!-- ko if: $data.prices -->
                    <div class="catalog-product__price">
                        <a data-bind="attr: {href: $data.url}">
                            <span data-bind="html: $data.prices.after"></span><span data-bind="text: formatCurrency($data.prices.min)"></span>
                        </a>
                        <!-- ko if: !$data.prices.after -->
                        <br/>
                        <br/>
                        <button type="button" class="btn btn-sm ml-5"
                            <? if (Yii::$app->user->isGuest) { ?>
                                onclick="window.location.href = '/signup/'"
                            <? } else { ?>
                                data-bind="attr: {id: 'btn-cart-'+$data.id}, click: function() { vmCart.addProductFromCategory($data.id); }"
                            <? } ?>
                            >В корзину
                        </button>
                        <input class="form-control product_in_stock" type="number" min="1" name="number" value="1" data-bind="attr: {id: 'count-'+$data.id}">
                        <!-- /ko -->
                    </div>
                    <!-- /ko -->

                    <!-- ko if: !$data.prices -->
                    <div class="catalog-product__status">Нет в наличии</div>
                    <!-- /ko -->

                    <!-- /ko -->
                </script>

                <script type="text/html" id="catalog-product__price-min-template">

                    <!-- ko if: $data.status === 'active' -->

                    <!-- ko if: $data.prices -->
                    <div class="catalog-product__price">
                        <a data-bind="attr: {href: $data.url}">
                            <span data-bind="html: $data.prices.after"></span><span data-bind="text: formatCurrency($data.prices.min)"></span>
                        </a>
                        <input class="form-control product_in_stock" type="number" min="1" name="number" value="1" data-bind="attr: {id: 'count-'+$data.id}">
                        <br/>
                        <button type="button" class="btn btn-sm ml-5 pull-right mt-10" data-bind="attr: {id: 'btn-cart-'+$data.id}, click: function() { vmCart.addProductFromCategory($data.id); }">В корзину</button>
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

                <div class="catalog-pagination" id="catalog-pagination" style="display: none;" data-bind="visible: $root.page.last() > 1, css: {'catalog-pagination_visible': $root.isVisible()}">
                    <a class="catalog-pagination__main" data-bind="click: $root.nextPage.bind($root),css: {'catalog-pagination__main_disabled': $root.page.current() == $root.page.last()}">
                        <span data-bind="text: $root.nextPageText"></span>
                    </a>
                    <div class="catalog-pagination__secondary">
                        <div class="catalog-pagination__dropdown" data-bind="click: $root.toggleDropdown.bind($root)">
                            <div class="catalog-pagination__dropdown-value" data-bind="text: $root.page.current"></div>
                            <div class="catalog-pagination__dropdown-items"></div>
                        </div>
                    </div>
                    <div class="catalog-pagination__pages" data-bind="css: {'catalog-pagination__pages_active': $root.isActiveDropdown}">
                        <div class="catalog-pagination__pages-container">
                            <ul class="catalog-pagination__pages-list" data-bind="foreach: {data: $root.pages, as: 'number'}">
                                <li class="catalog-pagination__pages-item" data-bind="css: {'catalog-pagination__pages-item_active': $root.page.current() === number}">
                                    <a class="catalog-pagination__pages-link" data-bind="click: function () { $root.setPage(number) }, text: number"></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>


                <div class="seo_text">
                    <?= $page['desc'];?>
                </div>


            </div>

        </div>

    </div>

</div>