<?
$bundle = \backend\themes\shop\pageAssets\catalog\element\form::register($this);

use yii\bootstrap\Html;

$id_measurement = ($model->id_measurement > 0) ? $model->id_measurement : 0;
echo '<div style="height: 40px;">';
echo '<div style="float: left;">';
echo '<input type="hidden" name="id_element" value="'.$model->id.'"/>';
echo '<input type="hidden" name="id_category" value="'.$model->id_category.'"/>';
echo '<input type="hidden" name="id_measurement" value="'.$id_measurement.'"/>';
echo Html::input('text','alias','',['placeholder'=>'url на товар онлайнера', 'class'=>'form-control', 'style'=>'width:300px;float:left;']);
echo Html::button('Парсить', ['class' => 'btn btn-primary btn-sm', 'style'=>'float:left;', 'id'=>'actionParseUpdate']);
echo '</div>';
echo '<div style="margin-left: 20px;    float: left;">';
echo '<p>Если товар уже есть, то обновится title, title_before, alias, desc_mini.<br/>Если характеристики уже есть, то они будут удалены и спарсены заново.</p>';
echo '</div>';
echo '</div>';
?>

<hr/>

<style>
    .props-group-head {
        background-color: #8da6ce;
        padding: 10px;
        font-weight: 600;
    }

    .props {
        padding: 10px;
    }

    .props:nth-child(2n) {
        background-color: #dadada;
    }
</style>


<!--pre data-bind="text: ko.toJSON($data.groups.groups, null, 2)"></pre-->

<!-- ko if: $data.groups.groups().length > 0 -->
<!-- ko foreach: { data: $data.groups.groups, as: 'group'  } -->

<div class="mt-20 mb-10 props-group-head" data-bind="text: group.name"></div>

<!-- ko if: group.fields().length > 0 -->
<!-- ko foreach: { data: group.fields, as : 'field'} -->
<div class="props">

    <!-- ko if: field.type() == '1' && field.type_dop() == 'default'  -->
    <div class="row" data-bind="template: {name: 'type1-default', data: field}"></div>
    <!-- /ko -->

    <!-- ko if: field.type() == '1' && field.type_dop() == 'time'  -->
    <div class="row" data-bind="template: {name: 'type1-time', data: field}"></div>
    <!-- /ko -->

    <!-- ko if: field.type() == '1' && field.type_dop() == 'mass'  -->
    <div class="row" data-bind="template: {name: 'type1-mass', data: field}"></div>
    <!-- /ko -->

    <!-- ko if: field.type() == '1' && field.type_dop() == 'kb'  -->
    <div class="row" data-bind="template: {name: 'type1-kb', data: field}"></div>
    <!-- /ko -->

    <!-- ko if: field.type() == '1' && field.type_dop() == 'with_one'  -->
    <div class="row" data-bind="template: {name: 'type1-with_one', data: field}"></div>
    <!-- /ko -->

    <!-- ko if: field.type() == '3' -->
    <div class="row" data-bind="template: {name: 'type3', data: field}"></div>
    <!-- /ko -->

    <!-- ko if: field.type() == '5' -->
    <div class="row" data-bind="template: {name: 'type5', data: field}"></div>
    <!-- /ko -->

    <!-- ko if: field.type() == '6' -->
    <div class="row" data-bind="template: {name: 'type6', data: field}"></div>
    <!-- /ko -->

</div>
<!-- /ko -->
<!-- /ko -->


<!-- /ko -->
<!-- /ko -->



<!--pre data-bind="text: ko.toJSON(groups, null, 2)"></pre-->

<script type="text/html" id="type1-default">
    <div class="col-lg-2" data-bind="text: field.name"></div>
    <div class="col-lg-10">
        <input placeholder="value" data-bind="value: field.field.value"/>
        <span data-bind="text: field.field.sign"></span>
        <input placeholder="text" data-bind="value: field.field.text"/>
    </div>
</script>

<script type="text/html" id="type1-time">
    <div class="col-lg-2" data-bind="text: field.name"></div>
    <div class="col-lg-10">
        <input placeholder="value.one" data-bind="value: field.field.value.one"/>
        <input placeholder="value.two" data-bind="value: field.field.value.two"/>
        <span data-bind="text: field.field.sign"></span>
        <input placeholder="text" data-bind="value: field.field.text"/>
    </div>
</script>

<script type="text/html" id="type1-mass">
    <div class="col-lg-2" data-bind="text: field.name"></div>
    <div class="col-lg-10">
        <input placeholder="value" data-bind="value: field.field.value"/>
        <input placeholder="text" data-bind="value: field.field.text"/>
    </div>
</script>

<script type="text/html" id="type1-kb">
    <div class="col-lg-2" data-bind="text: field.name"></div>
    <div class="col-lg-10">
        <input placeholder="value" data-bind="value: field.field.value"/>
        <input placeholder="text" data-bind="value: field.field.text"/>
    </div>
</script>

<script type="text/html" id="type1-with_one">
    <div class="col-lg-2" data-bind="text: field.name"></div>
    <div class="col-lg-10">
        <input placeholder="value" data-bind="value: field.field.value"/>
        <span data-bind="text: field.field.sign"></span>
        <input placeholder="text" data-bind="value: field.field.text"/>
        <div data-bind="foreach: {data:field.field.check, as : 'c' }">
            <input type="radio" data-bind="attr: { name: field.name }, radioCheck: active"> <span data-bind="text: c.val"></span>
        </div>
    </div>
</script>

<script type="text/html" id="type3">
    <div class="col-lg-2" data-bind="text: field.name"></div>
    <div class="col-lg-10">
        <div data-bind="foreach: {data:field.field.check, as : 'f' }">
            <input type="checkbox" data-bind="checked: active"/> <span data-bind="text: f.name"></span>&nbsp;
        </div>
        <span data-bind="text: field.field.sign"></span>
        <input placeholder="text" data-bind="value: field.field.text"/>
    </div>
</script>

<script type="text/html" id="type5">
    <div class="col-lg-2" data-bind="text: field.name"></div>
    <div class="col-lg-10">
        <textarea rows="3" cols="45" name="text" data-bind="value: field.field.value, valueUpdate: 'afterkeydown'"></textarea>
        <span data-bind="text: field.field.sign"></span>
    </div>
</script>

<script type="text/html" id="type6">
    <div class="col-lg-2" data-bind="text: field.name"></div>
    <div class="col-lg-10">
        <input placeholder="value.one" data-bind="value: field.field.value.one"/>
        <span data-bind="text: field.field.delemiter"></span>
        <input placeholder="value.two" data-bind="value: field.field.value.two"/>
        <span data-bind="text: field.field.sign"></span>
        <input placeholder="text" data-bind="value: field.field.text"/>
    </div>
</script>

<hr>
<button data-bind="click: $root.save">Сохранить</button>
<p>Перепроверка после сохранения обязательна. Скорее всего есть ошибки.</p>