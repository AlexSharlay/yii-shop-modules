<?
use common\modules\mods\mods_news\models\backend\News;

$colors = News::colors();
$news = News::find()->orderBy('col, row')->where('published=1')->asArray()->all();

$result = [];
foreach($news as $new) {
    $result[$new['col']][] = $new;
}

?>

<div class="row slides">
    <? foreach($result as $col) { ?>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
        <? if (count($col) == 1) { ?>
            <div class="mr-2 <? if ($col['0']['url']) echo 'hover'; ?>" <? if ($col['0']['url']) {
                    if ($col['0']['url_target']) {
                        echo 'onclick="window.open(\''.$col['0']['url'].'\')"';
                    } else {
                        echo 'onclick="location.href=\''.$col['0']['url'].'\'"';
                    }
                }
                ?>>
                <img src="/statics/mods/news/images/<?= $col['0']['image'] ?>">
                <span class="label label-<?= $colors[$col['0']['ico_color']] ?> slide-title"><?= $col['0']['ico_title'] ?></span>
                <span class="label label-desc slide-desc"><?= $col['0']['title'] ?></span>
            </div>
        <? } else if (count($col) == 2) { ?>
            <div class="mb-2 mr-2  <? if ($col['0']['url']) echo 'hover'; ?>" <? if ($col['0']['url']) {
                if ($col['0']['url_target']) {
                    echo 'onclick="window.open(\''.$col['0']['url'].'\')"';
                } else {
                    echo 'onclick="location.href=\''.$col['0']['url'].'\'"';
                }
            }
            ?>>
                <img src="/statics/mods/news/images/<?= $col['0']['image'] ?>">
                <span class="label label-<?=$colors[$col['0']['ico_color']]?> slide-title"><?=$col['0']['ico_title']?></span>
                <span class="label label-desc slide-desc"><?=$col['0']['title']?></span>
            </div>
            <div class="mr-2 pos-rel  <? if ($col['1']['url']) echo 'hover'; ?>" <? if ($col['1']['url']) {
                if ($col['1']['url_target']) {
                    echo 'onclick="window.open(\''.$col['1']['url'].'\')"';
                } else {
                    echo 'onclick="location.href=\''.$col['1']['url'].'\'"';
                }
            }
            ?>>
                <img src="/statics/mods/news/images/<?= $col['1']['image'] ?>">
                <span class="label label-<?=$colors[$col['1']['ico_color']]?> slide-title"><?=$col['1']['ico_title']?></span>
                <span class="label label-desc slide-desc"><?=$col['1']['title']?></span>
            </div>
        <? } ?>
    </div>
    <? } ?>
</div>