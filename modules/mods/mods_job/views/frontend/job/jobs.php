<?php
$bundle = \frontend\themes\shop\pageAssets\page\jobs::register($this);
?>

<div class="panel panel-flat jobs-page">
    <div class="panel-heading">
        <h5 class="panel-title">Вакансии в ООО "Сантехпром"</h5>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12">
                <p class="text-justify">
                    ООО «Сантехпром» на протяжении 20 лет реализует оптом и в розницу сантехнические изделия, керамическую плитку, мебель для ванных комнат и другие виды продукции зарубежных и отечественных производителей. Компания - официальный дилер в Республике Беларусь известных мировых производителей сантехнической продукции.
                </p>
                <p class="text-justify">
                    Мы заинтересованы в опытных, целеустремлённых, инициативных сотрудниках, ориентированных на результативную работу в команде, профессиональный рост и самореализацию.
                </p>
                <p class="text-justify">
                    Мы одна команда с единой целью - дальнейшее развитие и расширение бизнеса!
                </p>
                <br/>
                <ul class="media-list">
                    <? foreach($jobs as $department=>$vacancies) { ?>
                        <li class="media-header text-semibold"><span class="department"><?=$department;?></span></li>
                        <? foreach($vacancies as $vacancy) { ?>
                            <li class="media">
                                <div class="media-body pl-20">
                                    <div class="media-heading"><a href="/jobs/<?=$vacancy['id'];?>/"><?=$vacancy['vacancy'];?></a></div>
                                    <span class="text-muted"><?=$vacancy['salary'];?></span>
                                </div>

                            </li>
                        <? } ?>
                    <? } ?>
                </ul>
            </div>
        </div>
    </div>
</div>