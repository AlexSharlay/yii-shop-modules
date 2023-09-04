<?php
$bundle = \frontend\themes\shop\pageAssets\catalog\manufacturer::register($this);

$flats = $countries['flats'];
$countries = $countries['countries'];

?>

<div class="panel panel-flat" xmlns="http://www.w3.org/1999/html">
    <div class="panel-heading">
        <div class="row">
            <div class="col-md-3 col-lg-3 visible-lg visible-md hidden-sm">
                <?= $this->render('../manufacturer/_manufacturerMenu');?>
            </div>
            <div class="col-sm-12 col-md-9 col-lg-9 manufacturerPage">
                <!-- Страны -->
                <? if (is_array($countries) && count($countries)) { ?>
                <div>
                    <h5>Страны</h5>
                    <div>
                        <? foreach($countries as $country=>$manufacturers) { ?>
                        <div class="clearfix">

                        <h6 class="countriesPage-flat-img"><? if ($flats[$country]) echo '<img src="/statics/catalog/country/images/'.$flats[$country].'"> '; ?><?=$country;?></h6>
                        <? foreach($manufacturers as $manufacturer) { ?>
                        <a href="/manufacturer/<?=$manufacturer['alias'];?>/">
                            <div class="manufacturerBlock">
                                <img src="/statics/catalog/manufacturer/images/<?=($manufacturer['ico']) ? $manufacturer['ico'] : '_no.png';?>">
                                <span><?=$manufacturer['title'];?></span>
                            </div>
                        </a>
                        <? } ?>
                        </div>
                        <? } ?>
                    </div>
                </div>
                <? } ?>
                <!-- /страны -->

                <!-- Бренды -->
                <? /*if (is_array($manufacturers) && count($manufacturers)) { ?>
                <div>
                    <h5>Бренды</h5>
                    <div>
                        <? foreach($manufacturers as $manufacturer) { ?>
                        <a href="/manufacturer/<?=$manufacturer['alias'];?>/">
                            <div class="manufacturerBlock">
                                <img src="/statics/catalog/manufacturer/images/<?=($manufacturer['ico']) ? $manufacturer['ico'] : '_no.png';?>">
                                <span><?=$manufacturer['title'];?></span>
                            </div>
                        </a>
                        <? } ?>
                    </div>
                </div>
                <? }*/ ?>
                <!-- /бренды -->
            </div>
        </div>
    </div>
</div>





