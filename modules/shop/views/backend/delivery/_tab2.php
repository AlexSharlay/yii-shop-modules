<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\components\imperavi\Widget as Imperavi;
use yii\helpers\Url;

$bundle = \backend\themes\shop\pageAssets\shop\delivery\form::register($this);

$model = $paymentArray;

?>


<div class="row">
    <div class="col-xs-6">
        <div class="box">
            <div class="page-header">
                <div class="page-header-content">
                    <h3 class="page-title">Оплаты</h3>
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
                            <th>Заголовок</th>
                            <th>&nbsp;</th>
                        </tr>
                        </thead>
                        <tbody>
                        <? if (is_array($model) && count($model)>0) : ?>
                            <? foreach($model as $item) :?>
                                <tr data-key="110">
                                    <td><?=$item['id'];?></td>
                                    <td><?=$item['title'];?></td>
                                    <td>
                                        <a href="/backend/shop/payment/update/?id=<?=$item['id'];?>" title="Просмотр товара"><span class="glyphicon glyphicon-eye-open"></span></a>
                                        <a href="/backend/shop/delivery/delete-payment/?id_delivery=<?=$id_delivery;?>&id_payment=<?=$item['id'];?>" title="Удалить"><span class="glyphicon glyphicon-trash"></span></a>
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
                        <label><input type="search" class="" placeholder="Поиск оплат по заголовку..." style="width: 500px;" data-bind="value: searchTextDelivery, valueUpdate: 'afterkeydown', event: { keyup : $root.searchKeyboardCmdDelivery}"></label>
                        <!--pre data-bind="text: ko.toJSON($root, null, 2)"></pre-->
                    </div>
                </div>
                <div id="element-grid" class="dataTables_wrapper form-inline" role="grid">
                    <table class="table table-bordered table-hover dataTable">
                        <thead>
                        <tr>
                            <th>&nbsp;</th>
                            <th>ID</th>
                            <th>Заголовок</th>
                        </tr>
                        </thead>
                        <tbody data-bind="template: {
                            name: 'TableRowDelivery',
                            foreach: rowsDelivery
                        }">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script type="text/html" id="TableRowDelivery">
        <tr>
            <td><a data-bind="attr: { href: '/backend/shop/delivery/add-payment/?id_delivery=<?=$id_delivery;?>&id_payment=' + $data.id}"><i class="icon-plus3"></i></a></td>
            <td data-bind="text: id"></td>
            <td data-bind="text: title"></td>
        </tr>
    </script>
</div>