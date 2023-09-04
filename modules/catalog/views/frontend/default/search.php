<?php
$bundle = \frontend\themes\shop\pageAssets\page\search::register($this);
\common\modules\mods\mods_seo\components\seo::setMeta();
?>

<!-- Content area -->
<div class="content" id="search_content">

    <script>
        window.searchStr = '<?=$search;?>';
        window.SearchPage = 1;
    </script>

    <!-- Search field -->
    <div class="has-feedback has-feedback-left">
        <input type="text" class="form-control input-xlg" id="search"
               data-bind="textInput: searchString, event: { keyup: searchGo }, valueUpdate: 'afterkeydown'"
               placeholder="Поиск. Минимальная длина слова 3 символа.">

        <div class="form-control-feedback">
            <i class="icon-search4 text-muted text-size-base"></i>
        </div>
    </div>
    <!-- /search field -->

    <!-- Tabs -->
    <ul class="nav nav-lg nav-tabs nav-tabs-bottom search-results-tabs">
        <li class="pull-left hidden-xs">
            <ul class="nav nav-lg nav-tabs nav-tabs-bottom search-results-tabs">
                <li class="active"><a href="#"><i class="icon-display4 position-left"></i> Товары</a></li>
            </ul>
        </li>

        <!--li class="dropdown pull-right">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i data-bind="attr: { 'class' : sortNow.ico }"></i> <span data-bind="text : sortNow.title"></span>
                <span class=" position-right">Сортировка</span> <span class="caret"></span>
            </a>
            <ul class="dropdown-menu dropdown-menu-right" data-bind="foreach: { data: sort, as: 's' }">
                <li data-bind="
                    click: $parent.sortSelected,
                    css: { active: $parent.isSortChosen($data) }">
                    <a href="#"><i data-bind="attr: { 'class' : s.ico }"></i> <span data-bind="text : s.title"></span></a>
                </li>
            </ul>
        </li-->

        <li class="pull-right">
            <ul class="nav nav-lg nav-tabs nav-tabs-bottom" data-bind="foreach: { data: sort, as: 's' }">
                <li data-bind="click: $parent.sortSelected, css: { active: $parent.isSortChosen($data) }">
                    <a href="#"><i data-bind="attr: { 'class' : s.ico }"></i> <span data-bind="text : s.title"></span></a>
                </li>
            </ul>
        </li>
        <li class="pull-right hidden-xs">
            <ul class="nav nav-lg nav-tabs nav-tabs-bottom">
                <li class="sort-label">
                    <span>Сортировка: </span>
                </li>
            </ul>
        </li>

    </ul>
    <!-- /tabs -->


    <!-- <pre data-bind="text: ko.toJSON(category, null, 2)"></pre>-->
    <!-- Filter -->
    <div data-bind="if: categories().length > 1">
        <div class="panel panel-body">
            <div class="panel-heading">
                <h6 class="panel-title">Искать товары только в выбранной категории:</h6>
                <div class="heading-elements" style="right: 8px;">
                    <ul class="pager pager-sm">
                        <li><input type="button" class="btn btn-default" value="Очистить" data-bind="click: clearCategoryFilter"></li>
                    </ul>
                </div>
            </div>
            <div class="filter_categories" data-bind="foreach: { data: categories, as: 'category'}">
                <div class="filter_category" data-bind="click: $parent.category, css: { active: $parent.category().id === $data.id }">
                    <div class="filter_category-img">
                        <img data-bind="attr: { 'src' : img }">
                    </div>
                    <div class="filter_category-title">
                        <div data-bind="text: title"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /filter -->

    <!-- Search results -->
    <div class="panel panel-body">

        <p class="text-muted text-size-small">Найдено: <span data-bind="text : count"></span></p>

        <hr>

        <div class="row search-products" data-bind="foreach: { data: products, as: 'product'}">
            <div class="col-lg-2">
                <div data-bind="template: { name: 'product-template', data: product }"></div>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="col-lg-12" data-bind="if: pageAll().length > 0">
                <ul class="pagination pagination-flat pagination-xs no-margin-bottom" data-bind="foreach: { data: pageAll, as: 'page' }">
                    <li data-bind="click: $parent.pageSelected  , css: { active: $parent.isPageChosen(page) }"><a data-bind="text : page"></a></li>
                </ul>
            </div>
        </div>

    </div>
    <!-- /search results -->

 <!--   <pre data-bind="text: ko.toJSON(pageAll, null, 2)"></pre> -->

</div>


<script type="text/html" id="product-template">

    <div class="search-product">
        <div class="search-product-img">
            <a class="search-product-title" data-bind="attr: { 'href' : product.url }">
                <img data-bind="attr: { 'src' : product.photo, 'href' : product.url }"/>
            </a>
        </div>
        <a class="search-product-title" data-bind="text : product.title, attr: { 'href' : product.url }"></a>
        <span data-bind="if : product.price() > 1">
            <span class="search-product-price" data-bind="text : formatCurrency(product.price)"></span>
        </span>
<!--        <span data-bind="if : product.price_old() > 0">-->
<!--            <span class="search-product-price-old" data-bind="text : formatCurrency(product.price_old)"></span>-->
<!--        </span>-->
    </div>

</script>