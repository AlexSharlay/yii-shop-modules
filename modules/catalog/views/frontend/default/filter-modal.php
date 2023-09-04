<?php
use yii\helpers\Html;
?>
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

            <div class="catalog-filter-button" id="catalog-filter-button" style="display: none;"
                 data-bind="css: {'catalog-filter-button_fixed': $root.states.fixed,'catalog-filter-button_hanged': $root.states.hanged}, visible: true">
                <div class="catalog-filter-button__inner-container">
                    <div class="catalog-filter-button__inner"
                         data-bind="css: {'catalog-filter-button__inner_moved': $root.states.moved}, event: {mouseout: $root.onMouseout.bind($root), mouseover: $root.onMouseover.bind($root)}">
                        <div class="catalog-filter-button__state catalog-filter-button__state_initial catalog-filter-button__state_disabled"
                             data-bind="css: {'catalog-filter-button__state_control': $root.states.control, 'catalog-filter-button__state_animated': $root.states.animated}">
                                    <span class="catalog-filter-button__sub catalog-filter-button__sub_control"
                                          data-bind="click: $root.doMoved.bind($root)"></span>
                            <span class="catalog-filter-button__sub catalog-filter-button__sub_main"
                                  data-bind="html: $root.text, click: $root.apply.bind($root)"></span>
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

    <div class="adv_search_btn">
        <?= Html::button('Показать товары', ['id' => 'search_btn', 'style' => 'position:relative;', 'class' => 'btn smb', 'onclick' => 'reloadPage()']) ?>
    </div>

</aside>
</div>