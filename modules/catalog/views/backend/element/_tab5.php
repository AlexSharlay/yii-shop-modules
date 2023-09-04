<?
$bundle = \backend\themes\shop\pageAssets\catalog\element\form::register($this);
?>
<input type="hidden" id="id_element" value="<?=$model->id;?>"/>
<div class="row">
    <div class="col-xs-6">
        <div class="box">
            <div class="page-header">
                <div class="page-header-content">
                    <h3 class="page-title">Сборки</h3>
                    <div class="heading-elements">
                        <a class="btn btn-sm btn-default" href="/backend/catalog/element/index/" title="Cancel"><i class="icon icon-reply"></i> </a>
                    </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a>
                </div>
            </div>
            <div class="box-body table-responsive">
                <div id="element-grid" class="dataTables_wrapper form-inline" role="grid">
                    <table class="table table-bordered table-hover dataTable">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Набор</th>
                            <th>Картинка</th>
                            <th>Товар</th>
                            <th>Код</th>
                            <th>Цена</th>
                            <th>Порядок</th>
                            <th>&nbsp;</th>
                        </tr>
                        </thead>
                        <tbody data-bind="foreach: rowsKitList">
                        <tr>
                            <td data-bind="text: id"></td>
                            <td data-bind="text: num_kit"></td>
                            <td>{картинка}</td>
                            <td data-bind="text: title"></td>
                            <td data-bind="text: article"></td>
                            <td data-bind="text: price"></td>
                            <td data-bind="text: sort"></td>
                            <td><a data-bind="click : function() {$root.deleteKit(id_kit)}"><i class="icon-minus3"></i></a></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xs-6 mt-20">
        <div class="box">
            <div class="box-body table-responsive">
                <div class="datatable-header">
                    <div id="" class="dataTables_filter">
                        <label><input type="search" placeholder="Поиск товара по заголовку или артикулу..." style="width: 500px;" data-bind="value: searchTextKit, valueUpdate: 'afterkeydown', event: { keyup : $root.searchKits}"></label>
                        <label><input type="number" placeholder="Номер набора" style="width: 200px;" data-bind="value: numberTextKit"></label>
                        <!--pre data-bind="text: ko.toJSON($root, null, 2)"></pre-->
                    </div>
                </div>
                <div id="element-grid" class="dataTables_wrapper form-inline" role="grid">
                    <table class="table table-bordered table-hover dataTable">
                        <thead>
                        <tr>
                            <th>&nbsp;</th>
                            <th>ID</th>
                            <th>Картинка</th>
                            <th>Товар</th>
                            <th>Код</th>
                            <th>Цена</th>
                        </tr>
                        </thead>
                        <tbody data-bind="foreach: rowsKit">
                            <tr>
                                <td><a data-bind="click : function() {$root.addKits(id)}"><i class="icon-plus3"></i></a></td>
                                <td data-bind="text: id"></td>
                                <td>{картинка}</td>
                                <td data-bind="text: title"></td>
                                <td data-bind="text: article"></td>
                                <td data-bind="text: price"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


</div>
