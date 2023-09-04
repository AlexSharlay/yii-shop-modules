<?php
$bundle = \frontend\themes\shop\pageAssets\site\index::register($this);

\common\modules\mods\mods_seo\components\seo::setMeta([
    'seo_title' => 'Бонусная программа салона VENUE',
    'seo_desc' => 'Бонусная программа салона VENUE - совершайте покупки, накапливайте баллы и получайте скидки!'
]);

use yii\helpers\Html;

?>

<h1 class="rubric akcent alpha page_h1">
    <span>Бонусная программа KRANIK.BY</span>
</h1>
<br/>

<div class="row">
    <div class="col-md-6">
        <img src="/statics/web/site/files/bonus-cart2.jpg" class="bonus-cart">
    </div>

    <div class="col-md-6">
        <h1 class="title-page" style="color:#C21310">ДОБРО ПОЖАЛОВАТЬ</h1>
        <h1 class="title-page" style="font-size:24px;">В БОНУСНУЮ ПРОГРАММУ KRANIK.BY</h1>

        <div style="text-align:right;">
            <p class="under-title">1 балл = 1 рубль</p>
            <p class="under-title2">Выгода до 30%</p>

            <?php if (Yii::$app->user->isGuest): ?>
                <a href="/login/">
                    <button type="button btn-user" class="btn btn-enter">
                        <span class="glyphicon glyphicon-user" aria-hidden="true"></span> ВОЙТИ
                    </button>
                </a>
            <?php endif; ?>

        </div>
    </div>
</div>


<div>
    <a name="kak-rabotaet"></a>
    <h2 class="title-page title">Как это работает</h2>
    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-2 title-page4">
            <ul>
                <li><span style="title-page3">Шаг 1</span></li>
            </ul>
            <p class="title-page2">Оформите карту бесплатно до 31.12.2017</p>
            <p>при первой покупке от 50 рублей в нашем интернет-магазине</p>
        </div>
        <div class="col-md-2 title-page4">
            <ul>
                <li><span style="title-page3">Шаг 2</span></li>
            </ul>
            <p class="title-page2">Совершайте покупки</p>
            <p>предъявляйте карту при каждой покупке</p>
        </div>
        <div class="col-md-2 title-page4">
            <ul>
                <li><span style="title-page3">Шаг 3</span></li>
            </ul>
            <p class="title-page2">Накапливайте<br/>баллы</p>
            <p>получайте от 2% до 8% баллов</p>
            <a href="#more3"><p>узнать подробнее</p></a>
        </div>

        <!-- Модальное окно-->
        <a href="#x" class="overlay-bonus" id="more3"></a>
        <div class="popup-bonus">
            <p>Количество начисляемых баллов зависит от суммы покупки и приобретенных товаров.</p>

            <table border="1" style="margin: 0 auto;" class="table table-striped table-hover table-bordered">
                <tr>
                    <td colspan="4" style="text-align:center; color:#c21310; font-weight:700;">Cантехника</td>
                </tr>
                <tr>
                    <td style="text-align:center;">до 999 рублей</td>
                    <td style="text-align:center;">от 1000 до 1999 рублей</td>
                    <td style="text-align:center;">от 2000 до 3999 рублей</td>
                    <td style="text-align:center;">от 4000 рублей</td>
                </tr>
                <tr>
                    <td style="text-align:center;">2%</td>
                    <td style="text-align:center;">3%</td>
                    <td style="text-align:center;">5%</td>
                    <td style="text-align:center;">7%</td>
                </tr>
                <tr>
                    <td colspan="4" style="text-align:center; color:#c21310; font-weight:700;">Керамическая плитка</td>
                </tr>
                <tr>
                    <td style="text-align:center;">до 999 рублей</td>
                    <td style="text-align:center;">от 1000 до 1999 рублей</td>
                    <td style="text-align:center;">от 2000 до 3999 рублей</td>
                    <td style="text-align:center;">от 4000 рублей</td>
                </tr>
                <tr>
                    <td style="text-align:center;">2%</td>
                    <td style="text-align:center;">3%</td>
                    <td style="text-align:center;">5%</td>
                    <td style="text-align:center;">7%</td>
                </tr>
                <tr>
                    <td colspan="4" style="text-align:center; color:#c21310; font-weight:700;">Сантехника + керамическая плитка</td>
                </tr>
                <tr>
                    <td style="text-align:center;">до 1999 рублей</td>
                    <td style="text-align:center;">от 2000 до 3999 рублей</td>
                    <td style="text-align:center;">от 4000 до 7999 рублей</td>
                    <td style="text-align:center;">от 8000 рублей</td>
                </tr>
                <tr>
                    <td style="text-align:center;">3%</td>
                    <td style="text-align:center;">4%</td>
                    <td style="text-align:center;">6%</td>
                    <td style="text-align:center;">8%</td>
                </tr>
            </table>

            <a class="close" title="Закрыть" href="#close"></a>
        </div>


        <div class="col-md-2 title-page4">
            <ul>
                <li><span style="title-page3">Шаг 4</span></li>
            </ul>
            <p class="title-page2">Расплачивайтесь баллами</p>
            <p>накопленными баллами вы можете оплатить до 30% от стоимости покупки</p>
        </div>
        <div class="col-md-2 title-page4">
            <ul>
                <li><span style="title-page3">Шаг 5</span></li>
            </ul>
            <p class="title-page2">Получайте<br/>подарки</p>
            <p>зависит от годовой суммы заказа (минимум 10 000 рублей)</p>
            <a href="#more5"><p>узнать подробнее</p></a>
        </div>

        <!-- Модальное окно-->
        <a href="#x" class="overlay-bonus" id="more5"></a>
        <div class="popup-bonus">
            <p>Дополнительный годовой бонус. </p>

            <table border="1" style="margin: 0 auto;" class="table table-striped table-hover table-bordered">

                <tr>
                    <td style="text-align:center; color:#c21310; font-weight:700;">Общая сумма покупок за 365 дней (с даты первой покупки)</td>
                    <td style="text-align:center; color:#c21310; font-weight:700;">Подарок</td>
                    <td style="text-align:center; color:#c21310; font-weight:700;">Описание</td>
                </tr>
                <tr>
                    <td style="text-align:center;">от 10 000 до 29 999 рублей</td>
                    <td style="text-align:center;">Несессер «Пуэрто-Рико»</td>
                    <td style="text-align:center;">Размеры - 22х23, 5х11 см. Материал - хлопок, 50%; полиэстер, 50%</td>
                </tr>
                <tr>
                    <td style="text-align:center;">от 30 000 до 49 999 рублей</td>
                    <td style="text-align:center;">Набор «Королевское утро»</td>
                    <td style="text-align:center;">Материал – фарфор. Состав: кофейная пара – 2 шт (чашка + блюдце), блюдо, солонка, подставка под яйцо – 2 шт, подарочная упаковка.</td>
                </tr>
                <tr>
                    <td style="text-align:center;">от 50 000 рублей</td>
                    <td style="text-align:center;">Коллекция аксессуаров Marsala или Palermo (на выбор)</td>
                    <td style="text-align:center;">Зонт-полуавтомат. Кошелек. Материал – натуральная кожа.</td>
                </tr>
            </table>

            <a class="close" title="Закрыть" href="#close"></a>
        </div>

    </div>
</div>


<div class="row">
    <a name="voprosy"></a>
    <h2 class="title-page title">Часто задаваемые вопросы</h2>

    <div class=col-md-12>
        <input class="hide" id="hd-1" type="checkbox">
        <label for="hd-1">Как получить бонусную карту?</label>
        <div>Для того, чтобы получить бонусную карту, заполните анкету при покупке товара на сайте kranik.by.</div>
        <br/>
        <input class="hide" id="hd-2" type="checkbox">
        <label for="hd-2">Какая стоимость бонусной карты?</label>
        <div>С 18.12.2017 по 31.12.2017 выдается каждому покупателю бесплатно, а с 01.01.2018 при покупке на сумму не менее 50 рублей стоимость карты составит
            2 рубля.
        </div>
        <br/>
        <input class="hide" id="hd-3" type="checkbox">
        <label for="hd-3">Где я могу воспользоваться бонусной картой?</label>
        <div>В интерне-магазине KRANIK.BY.</div>
        <br/>
        <input class="hide" id="hd-4" type="checkbox">
        <label for="hd-4">Как будет использована информация, указанная мной в анкете?</label>
        <div>Информация будет использована для формирования и направления наиболее интересных для вас предложений.</div>
        <br/>
        <input class="hide" id="hd-5" type="checkbox">
        <label for="hd-5">Какие покупки учитываются на бонусной карте?</label>
        <div>Все покупки без исключения, если вы предъявляете бонусную карту при покупке. При этом, увеличивая сумму накоплений, вы увеличиваете сумму баллов,
            которые начисляются за каждую покупку!
        </div>
        <br/>
        <input class="hide" id="hd-6" type="checkbox">
        <label for="hd-6">Могу ли я передавать свою бонусную карту друзьям или родственникам?</label>
        <div>Да, так как бонусная карта не является именной, т.е. действует на предъявителя. Таким образом вы можете передавать свою карту друзьям или
            родственникам для накопления и/или траты баллов.
        </div>
        <br/>
        <input class="hide" id="hd-7" type="checkbox">
        <label for="hd-7">Что такое баллы и как ими воспользоваться?</label>
        <div>За покупки на вашу бонусную карту начисляются баллы в соответствии с Вашим единоразовым заказом. Подробнее в таблице.
            <br/><br/>
            <table border="1" style="margin: 0 auto; width: 555px;" class="table table-striped table-hover table-bordered">
                <tr>
                    <td colspan="4" style="text-align:center; color:#c21310; font-weight:700;">Cантехника</td>
                </tr>
                <tr>
                    <td style="text-align:center;">до 999 рублей</td>
                    <td style="text-align:center;">от 1000 до 1999 рублей</td>
                    <td style="text-align:center;">от 2000 до 3999 рублей</td>
                    <td style="text-align:center;">от 4000 рублей</td>
                </tr>
                <tr>
                    <td style="text-align:center;">2%</td>
                    <td style="text-align:center;">3%</td>
                    <td style="text-align:center;">5%</td>
                    <td style="text-align:center;">7%</td>
                </tr>
                <tr>
                    <td colspan="4" style="text-align:center; color:#c21310; font-weight:700;">Керамическая плитка</td>
                </tr>
                <tr>
                    <td style="text-align:center;">до 999 рублей</td>
                    <td style="text-align:center;">от 1000 до 1999 рублей</td>
                    <td style="text-align:center;">от 2000 до 3999 рублей</td>
                    <td style="text-align:center;">от 4000 рублей</td>
                </tr>
                <tr>
                    <td style="text-align:center;">2%</td>
                    <td style="text-align:center;">3%</td>
                    <td style="text-align:center;">5%</td>
                    <td style="text-align:center;">7%</td>
                </tr>
                <tr>
                    <td colspan="4" style="text-align:center; color:#c21310; font-weight:700;">Сантехника + керамическая плитка</td>
                </tr>
                <tr>
                    <td style="text-align:center;">до 1999 рублей</td>
                    <td style="text-align:center;">от 2000 до 3999 рублей</td>
                    <td style="text-align:center;">от 4000 до 7999 рублей</td>
                    <td style="text-align:center;">от 8000 рублей</td>
                </tr>
                <tr>
                    <td style="text-align:center;">3%</td>
                    <td style="text-align:center;">4%</td>
                    <td style="text-align:center;">6%</td>
                    <td style="text-align:center;">8%</td>
                </tr>
            </table>
            <br/>
            Вы можете оплачивать баллами до 30% стоимости товара. На часть покупки, оплаченную бонусными баллами, баллы не начисляются – они будут начислены на
            часть покупки, оплаченную денежными средствами.

        </div>
        <br/>
        <input class="hide" id="hd-8" type="checkbox">
        <label for="hd-8">На все ли товары начисляются баллы?</label>
        <div>Баллы начисляются на все товары, кроме тех, которые участвуют в акциях.</div>
        <br/>
        <input class="hide" id="hd-9" type="checkbox">
        <label for="hd-9">Какой курс оплаты товара баллами?</label>
        <div>1 балл = 1 белорусский рубль.</div>
        <br/>
        <input class="hide" id="hd-10" type="checkbox">
        <label for="hd-10">Могу я оплатить баллами всю сумму покупки?</label>
        <div>Нет. Баллами можно оплатить до 30 % от стоимости товара при условии, что на вашем счете присутствует достаточное для данной операции количество
            баллов.
            <br/><br/>Пример. Товар продается стоимостью 1000 рублей. Максимальная часть, которая может быть оплачена баллами, составляет не более 300 рублей,
            что равно использованию 300 баллов и составляет 30% от полной розничной цены, установленной до применения любых скидок. Оставшуюся часть в 700
            рублей вы оплачиваете наличными или банковской картой.
        </div>
        <br/>
        <input class="hide" id="hd-11" type="checkbox">
        <label for="hd-11">Сколько времени действительны накопленные баллы?</label>
        <div>Баллы являются срочными. Неиспользованные баллы аннулируются через 6 месяцев после даты последней покупки.</div>
        <br/>
        <input class="hide" id="hd-12" type="checkbox">
        <label for="hd-12">Когда баллы появятся на моем счету?</label>
        <div>Спустя несколько минут после совершения покупки.</div>
        <br/>
        <input class="hide" id="hd-13" type="checkbox">
        <label for="hd-13">Можно ли баллы обменять на наличные деньги или использовать на оплату доставки?</label>
        <div>Нет. Баллы нельзя обменивать на наличные деньги, а использовать только на оплату товаров.</div>
        <br/>
        <input class="hide" id="hd-14" type="checkbox">
        <label for="hd-14">Если я потерял бонусную карту, могу ли я ее восстановить?</label>
        <div>Да, можете. Для этого нужно обратиться к администратору интернет-магазина KRANIK.BY. Все баллы и общая сумма покупок перейдут на новую бонусную карту. Об
            утере бонусной карты необходимо в кратчайшие сроки сообщить по телефону или на e-mail, так как человек, нашедший карту, может
            потратить баллы до того, как вам выдадут новую. Восстановить баллы в этом случае будет невозможно.
        </div>
        <br/>
        <input class="hide" id="hd-15" type="checkbox">
        <label for="hd-15">Если я возвращаю товар, что произойдет с моими баллами?</label>
        <div>Баллы, начисленные за этот товар, будут аннулированы. В случае, когда производится возврат товара, оплаченного баллами, возврат денежной суммы
            производится в соответствии с фактически оплаченной клиентом суммой (т.е. без учета бонуса).
            <br/><br/>
            Пример. Вы приобретаете товар на сумму 700 рублей. Бонусами было оплачено 210 рублей (30% от суммы), 490 рублей вы вносите своими деньгами. При
            возврате товара интернет-магазин KRANIK.BY возвращает вам 490 рублей (дополнительно может быть вычтена стоимость доставки, если таковая имела место быть).
        </div>
        <br/>
        <input class="hide" id="hd-16" type="checkbox">
        <label for="hd-16">Как изменить мою контактную информацию?</label>
        <div>В личном кабинете на сайте вы можете изменять контактную информацию.</div>
        <br/>
        <input class="hide" id="hd-17" type="checkbox">
        <label for="hd-17">Как мне узнать, сколько баллов у меня на бонусной карте?</label>
        <div>В любом салоне по предъявлению карты сотруднику салона, либо на сайте в личном кабинете вы можете посмотреть количество баллов. В Личном кабинете
            на сайте вы сможете оперативно контролировать состояние бонусного счета.
        </div>
        <br/>
        <input class="hide" id="hd-18" type="checkbox">
        <label for="hd-18">Есть ли у вас подарки в честь дня рождения?</label>
        <div>Да. Вам начисляется 50 баллов первого числа в месяц вашего рождения. Воспользоваться ими можно в любой день с 1 по 30 (31) число месяца, когда у вас день рождения. Оплатить баллами не более 30% от стоимости товара.
        </div>
        <br/>
        <input class="hide" id="hd-19" type="checkbox">
        <label for="hd-19">Участвуют ли товары, купленные по акции, в накопительной годовой программе?</label>
        <div>В накопительной годовой программе участвуют все товары (в том числе акционные и купленными с помощью баллов).</div>
        <br/>
        <input class="hide" id="hd-20" type="checkbox">
        <label for="hd-20">Как происходит подсчет по накопительной программе и выдача подарков?</label>
        <div>С даты первой покупки начинает действовать накопительная программа, срок – 365 дней. По истечение данного срока данные обнуляются. По истечению
            срока в личном кабинете или позвонив в розничную точку, вы можете узнать накопленную сумму и полагающийся подарок.
        </div>

    </div>
</div>


<div class="row">
    <a name="pravila"></a>
    <h2 class="title-page title">Правила участия</h2>

    <div class=col-md-12>

        <input class="hide" id="hd-0" type="checkbox">
        <label for="hd-0">Ознакомиться с правилами участия (юридическая информация)</label>
        <div>
            <br/>
            Правила участия бонусной программы доступны по <a href="/statics/web/site/files/pravila_bonus_2017.pdf?v1" target="_blank">ссылке</a>.
        </div>
        <br/><br/>


        <?php if (Yii::$app->user->isGuest): ?>
            <a href="/login/">
                <button type="button btn-user" class="btn btn-enter">
                    <span class="glyphicon glyphicon-user" aria-hidden="true"></span> ВОЙТИ В ЛИЧНЫЙ КАБИНЕТ
                </button>
            </a>
        <?php endif; ?>

    </div>
</div>
