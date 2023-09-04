<?
use \common\modules\catalog\components\Helper;
$bundle = \backend\themes\shop\pageAssets\catalog\element\form::register($this);

$model = $models['elements'];
$id_model = $models['id_parent'];


?>
<div class="row">
    <div class="col-xs-6">
        <div class="box">
            <div class="page-header">
                <div class="page-header-content">
                    <h3 class="page-title">Модели</h3>
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
                            <th>Картинка</th>
                            <th>Товар</th>
                            <th>Код</th>
                            <th>Цена</th>
                            <th>&nbsp;</th>
                        </tr>
                        </thead>
                        <tbody>
                        <? if (is_array($model) && count($model)>0) : ?>
                            <? foreach($model as $item) :?>
                                <tr data-key="110">
                                    <td><?=$item['id'];?></td>
                                    <td>{сделать потом}</td>
                                    <td><?=$item['title'];?></td>
                                    <td><?=$item['article'];?></td>
                                    <td>
                                        <?
                                        if ($item['price']!=0) echo Helper::formatPrice($item['price']);
                                        ?>
                                    </td>
                                    <td>
                                        <a href="/backend/catalog/element/update/?id=<?=$item['id'];?>" title="Просмотр товара"><span class="glyphicon glyphicon-eye-open"></span></a>
                                        <a href="/backend/catalog/element/delete-from-model/?id_model=<?=$id_model;?>&id_element=<?=$item['id'];?>" title="Удалить из моделей"><span class="glyphicon glyphicon-trash"></span></a>
                                    </td>
                                </tr>
                            <? endforeach;?>
                        <? endif; ?>
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
                        <label><input type="search" class="" placeholder="Поиск товара по заголовку или артикулу..." style="width: 500px;" data-bind="value: searchTextModel, valueUpdate: 'afterkeydown', event: { keyup : $root.searchKeyboardCmdModel}"></label>
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
                        <tbody data-bind="template: {
                            name: 'TableRowModel',
                            foreach: rowsModel
                        }">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script type="text/html" id="TableRowModel">
        <tr>
            <td><a data-bind="attr: { href: '/backend/catalog/element/add-to-model/?id_model=<?=$id_model;?>&id_element=' + $data.id}"><i class="icon-plus3"></i></a></td>
            <td data-bind="text: id"></td>
            <td>{картинка}</td>
            <td data-bind="text: title"></td>
            <td data-bind="text: article"></td>
            <td data-bind="text: price"></td>
        </tr>
    </script>
</div>
