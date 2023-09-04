<?
use common\modules\mods\mods_manufacturer\models\Manufacturer;

$manufacturers = Manufacturer::find()->orderBy('sort')->asArray()->all()
?>

<div class="index-manufacturer">
    <div id="homeManufacturer">
        <? foreach($manufacturers as $manufacturer) { ?>
            <div class="homeManufacturer-slide <? if ($manufacturer['url']) echo 'pointer'; ?>" role="button" <?if ($manufacturer['url']) echo ' onclick="location.href=\''.$manufacturer['url'].'\'";'?> >
                <div class="homeManufacturer-slide-title">
                    <img src="/statics/mods/manufacturer/images/<?=$manufacturer['ico'];?>"/>
                </div>
                <div class="homeManufacturer-slide-text">
                    <?=$manufacturer['title'];?>
                </div>
            </div>
        <? } ?>
    </div>
</div>