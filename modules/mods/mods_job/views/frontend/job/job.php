<?
$bundle = \frontend\themes\shop\pageAssets\shop\order::register($this);
?>

<div class="panel panel-flat jobs-page">
    <div class="panel-heading">
        <h4><a href="/jobs/"><i class="icon-arrow-left52 position-left"></i></a> <?=$job['vacancy'];?></h4>
    </div>
    <div class="panel-body">

        <?=$job['content'];?>
        <hr/>

        <div class="row mt-20">

            <div class="col-lg-3">
                <h3 style="margin-top: 0">Контактная информация</h3>
                <div>
                    <img src="<?=$bundle->baseUrl;?>/images/phones/velcom.png">&nbsp;
                    <span>+375 (29) 635-36-91</span>
                </div>
                <div>
                    <img src="<?=$bundle->baseUrl;?>/images/phones/velcom.png">&nbsp;
                    <span>+375 (29) 1-492-492</span>
                </div>
                <div>
                    <img src="<?=$bundle->baseUrl;?>/images/phones/mts.png">&nbsp;
                    <span>+375 (29) 2-036-036</span>
                </div>
                <div>
                    <img src="<?=$bundle->baseUrl;?>/images/phones/phone.png">&nbsp;
                    <span>+375 (17) 388-19-99 (вн.189)</span>
                </div>
                <div>
                    <img src="<?=$bundle->baseUrl;?>/images/phones/email.png" width="20">&nbsp;
                    <span><a href="mailto:personal@shop.by">personal@shop.by</a></span>&nbsp;
                </div>
            </div>
            <div class="col-lg-9">
                <!--                <img src="--><?//=$bundle->baseUrl;?><!--/images/job/hr.jpg" width="125" style="margin-left: 30px;">-->
                <br/>
                <span></span>
            </div>
        </div>
    </div>
</div>