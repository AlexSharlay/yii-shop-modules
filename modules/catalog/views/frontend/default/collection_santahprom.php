<?php
$bundle = \frontend\themes\shop\pageAssets\catalog\collection::register($this);
\common\modules\mods\mods_seo\components\seo::setMeta();
use common\modules\catalog\components\Helper;
?>

<div class="breadcrumb-line">
    <ul class="breadcrumb breadcrumb_upd">
        <li>
            <a href="/collections/<?=$category['alias'];?>/"><?=$category['title'];?></a>
        </li>
        <li class="active">
            Коллекция: <?=$collection['title'];?>
        </li>

    </ul>
</div>
<div class="panel panel-white">
    <div class="panel-heading">
        <h5 class="panel-title">Коллекция: <?=$collection['title'];?></h5>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-5">
                <div data-bind="if: product.photos">
                    <ul id="imageGallery" class="imageGallery" >
                        <? if (is_array($collection['photos']) && count($collection['photos'])) { ?>
                        <? foreach($collection['photos'] as $photo) { ?>
                        <li data-thumb="/statics/catalog/photo/images/<?=$photo;?>">
                            <img src="/statics/catalog/photo/images/<?=$photo;?>" href="/statics/catalog/photo/images/<?=$photo;?>"/>
                        </li>
                        <? } ?>
                        <? } ?>
                    </ul>
                </div>
                <hr/>
                <div>
                    <? if ($collection['desc']) { ?>
                    <h6>Описание:</h6>
                    <?= $collection['desc']; ?>
                    <? } ?>
                </div>
            </div>
            <div class="col-lg-8 col-md-8 col-sm-7">
                <? if (is_array($elements) && count($elements) && $elements['0']['alias']) { ?>
                <div class="collection_product-list">
                    <? foreach($elements as $element) { ?>
                        <div class="collection_product">
                            <div class="collection_product-img">
                                <a href="/catalog/<?=$element['category'];?>/<?=$element['manufacturer'];?>/<?=$element['alias'];?>/">
                                    <img src="/statics/catalog/photo/images/<?=$element['photo'];?>"/>
                                </a>
                            </div>
                            <a href="/catalog/<?=$element['category'];?>/<?=$element['manufacturer'];?>/<?=$element['alias'];?>/">
                                <?
                                $str = '';
                                if ($element['title_before']) $str .= $element['title_before'].' ';
                                if ($element['manufacturerTitle']) $str .= $element['manufacturerTitle'].' ';
                                $str .= $element['title'];
                                echo $str;
                                ?>
                            </a>
                            <div><?=Helper::formatPrice($element['price']);?></div>
                        </div>
                    <? } ?>
                </div>
                <? } ?>
            </div>
        </div>


    </div>
</div>