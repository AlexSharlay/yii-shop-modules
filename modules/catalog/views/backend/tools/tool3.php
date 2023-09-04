<?php
$bundle = \backend\themes\shop\pageAssets\catalog\tools\tool3::register($this);
?>

<div style="height: :100%;margin:0 20px;">
    <div style="height:900px;overflow:scroll;float:left;width:1037px;">
        <div>
            <b>izm_in_text</b> - в значении поля содержится измерение<br/>
        </div>
        <div>
            Поиск самых заполненных товаров: <input type="text" id="start6-inp" placeholder="alias"/> <button id="start6-btn">Старт</button>
        </div>
        <div style="height: 70px;">
            Заполнить из интернета: <input type="text" id="start1-inp" placeholder="tx201+panaskxts2350+gigaset_dx800a" value=""/> <button id="start1-btn">Старт</button> | <input type="text" id="start2-inp" placeholder="category-id" style="width:100px;"/> <button id="start2-btn">Добавить/Обновить SQL</button> | <input type="text" id="start4-inp" placeholder="category-id" style="width:100px;"/> <button id="start4-btn">Получить из SQL</button><br/>
            <button id="start5-btn" style="float:left;">Добавить поле</button> <button id="start3-btn" style="float:right;">Обновить сортировку</button>
        </div>

        <table class="table-striped table-bordered tool1">
            <head>
                <tr>
                    <td>group/title/alias/desc</td>
                    <td>Тип</td>
                    <td>dop</td>
                    <td>izm</td>
                    <td>vars</td>
                    <td>compare</td>
                    <td>sort</td>
                    <td>publ</td>
                </tr>
            </head>
        </table>
        <br/>

        <div class="sortable-fields">
            <form id="hide">
                <input type="hidden" id="field_id">
                <table class="table-striped table-bordered tool1">
                <table class="table-striped table-bordered tool1">
                    <tbody>
                    <tr>
                        <td>
                            <input type="text" id="group" placeholder="group"/><br/>
                            <input type="text" id="title" placeholder="title"/><br/>
                            <input type="text" id="title_filter" placeholder="title_filter"/><br/>
                            <input type="text" id="alias" placeholder="alias"/><br/>
                            <textarea id="descr"></textarea>
                        </td>
                        <td>
                            <input type="radio" id="type1" name="type1" value="1"> 1<br/>
                            <input type="radio" id="type3" name="type1" value="3"> 3<br/>
                            <!--input type="radio" id="type4" name="type1" value="4"> 4<br/-->
                            <input type="radio" id="type5" name="type1" value="5"> 5<br/>
                            <input type="radio" id="type6" name="type1" value="6"> 6
                        </td>
                        <td>
                            <div style="float:left;margin-right: 5px;">
                                1<br/>
                                <input type="radio" id="dop11" name="dop1" value="default"> default<br/>
                                <input type="radio" id="dop12" name="dop1" value="time"> time<br/>
                                <input type="radio" id="dop13" name="dop1" value="mass"> mass<br/>
                                <input type="radio" id="dop14" name="dop1" value="kb"> kb<br/>
                                <input type="radio" id="dop15" name="dop1" value="with_one"> with_one<br/><br/>
                            </div>
                            <div style="float:left;">
                                3<br/>
                                <input type="checkbox" name="dop3_check_izm_in_text"/> izm_in_text<br/>
                                6<br/>
                                <input type="text" name="dop6" placeholder="разделитель" style="width: 100px;"/>
                            </div>
                        </td>
                        <td><input type="text" id="metering"/></td>
                        <td>
                            <textarea placeholder="vars" class="vars"></textarea>
                            <textarea placeholder="copy" class="copy"></textarea>
                            <textarea placeholder="val_in_text" class="val_in_text"></textarea>
                        </td>
                        <td>
                            <input type="radio" id="compare1" name="compare" value="best_max"> best_max<br/>
                            <input type="radio" id="compare2" name="compare" value="best_min"> best_min<br/>
                            <input type="radio" id="compare3" name="compare" value="best_yes"> best_yes<br/>
                            <input type="radio" id="compare4" name="compare" value="best_no"> best_no<br/>
                            <input type="radio" id="compare5" name="compare" value="none"> none<br/>
                            <input type="radio" id="compare6" name="compare" value="best_count_max"> best_count_max<br/>
                        </td>
                        <td><input type="number" class="sort_full"/></td>
                        <td>
                            <input type="checkbox" class="publ" checked="checked"/>
                            <input type="button" class="delete-line" value="Удалить">
                        </td>
                    </tr>
                    </tbody>
                </table>
            </form>
        </div>
    </div>

    <div style="height:900px;overflow:scroll;float:left;width:690px;">
        <div id="panel-vars">
            <table id="table1">
                <thead>
                <tr>
                    <td>title</td>
                    <td>alias</td>
                    <td>db_val</td>
                    <td>sort</td>
                    <td>n</td>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><input type="text"></td>
                    <td><input type="text"></td>
                    <td><input type="text" value=""></td>
                    <td><input type="text" value=""></td>
                    <td>1</td>
                </tr>
                <tr>
                    <td><input type="text"></td>
                    <td><input type="text"></td>
                    <td><input type="text" value=""></td>
                    <td><input type="text" value=""></td>
                    <td>2</td>
                </tr>
                <tr>
                    <td><input type="text"></td>
                    <td><input type="text"></td>
                    <td><input type="text" value=""></td>
                    <td><input type="text" value=""></td>
                    <td>3</td>
                </tr>
                <tr>
                    <td><input type="text"></td>
                    <td><input type="text"></td>
                    <td><input type="text" value=""></td>
                    <td><input type="text" value=""></td>
                    <td>4</td>
                </tr>
                <tr>
                    <td><input type="text"></td>
                    <td><input type="text"></td>
                    <td><input type="text" value=""></td>
                    <td><input type="text" value=""></td>
                    <td>5</td>
                </tr>
                <tr>
                    <td><input type="text"></td>
                    <td><input type="text"></td>
                    <td><input type="text" value=""></td>
                    <td><input type="text" value=""></td>
                    <td>6</td>
                </tr>
                <tr>
                    <td><input type="text"></td>
                    <td><input type="text"></td>
                    <td><input type="text" value=""></td>
                    <td><input type="text" value=""></td>
                    <td>7</td>
                </tr>
                <tr>
                    <td><input type="text"></td>
                    <td><input type="text"></td>
                    <td><input type="text" value=""></td>
                    <td><input type="text" value=""></td>
                    <td>8</td>
                </tr>
                <tr>
                    <td><input type="text"></td>
                    <td><input type="text"></td>
                    <td><input type="text" value=""></td>
                    <td><input type="text" value=""></td>
                    <td>9</td>
                </tr>
                <tr>
                    <td><input type="text"></td>
                    <td><input type="text"></td>
                    <td><input type="text" value=""></td>
                    <td><input type="text" value=""></td>
                    <td>10</td>
                </tr>
                <tr>
                    <td><input type="text"></td>
                    <td><input type="text"></td>
                    <td><input type="text" value=""></td>
                    <td><input type="text" value=""></td>
                    <td>11</td>
                </tr>
                <tr>
                    <td><input type="text"></td>
                    <td><input type="text"></td>
                    <td><input type="text" value=""></td>
                    <td><input type="text" value=""></td>
                    <td>12</td>
                </tr>
                <tr>
                    <td><input type="text"></td>
                    <td><input type="text"></td>
                    <td><input type="text" value=""></td>
                    <td><input type="text" value=""></td>
                    <td>13</td>
                </tr>
                <tr>
                    <td><input type="text"></td>
                    <td><input type="text"></td>
                    <td><input type="text" value=""></td>
                    <td><input type="text" value=""></td>
                    <td>14</td>
                </tr>
                <tr>
                    <td><input type="text"></td>
                    <td><input type="text"></td>
                    <td><input type="text" value=""></td>
                    <td><input type="text" value=""></td>
                    <td>15</td>
                </tr>
                <tr>
                    <td><input type="text"></td>
                    <td><input type="text"></td>
                    <td><input type="text" value=""></td>
                    <td><input type="text" value=""></td>
                    <td>16</td>
                </tr>
                <tr>
                    <td><input type="text"></td>
                    <td><input type="text"></td>
                    <td><input type="text" value=""></td>
                    <td><input type="text" value=""></td>
                    <td>17</td>
                </tr>
                <tr>
                    <td><input type="text"></td>
                    <td><input type="text"></td>
                    <td><input type="text" value=""></td>
                    <td><input type="text" value=""></td>
                    <td>18</td>
                </tr>
                <tr>
                    <td><input type="text"></td>
                    <td><input type="text"></td>
                    <td><input type="text" value=""></td>
                    <td><input type="text" value=""></td>
                    <td>19</td>
                </tr>
                <tr>
                    <td><input type="text"></td>
                    <td><input type="text"></td>
                    <td><input type="text" value=""></td>
                    <td><input type="text" value=""></td>
                    <td>20</td>
                </tr>
                </tbody>
            </table>
            <p>
                <button id="click1" style="width: 100%">Добавить 10 строк</button>
            </p>
            <p>
                <button id="click2" style="width: 100%">Серилизовать</button>
            </p>
            <p>
                Вставить новый элемент без потери db_val
            </p>
            <p>
            <table id="add_el">
                <tr>
                    <td><input type="text" id="addt" placeholder="title"></td>
                    <td><input type="text" id="adda" placeholder="alias"></td>
                    <td><input type="text" id="addd" placeholder="db_val"></td>
                    <td><input type="text" id="adds" placeholder="sort"></td>
                    <td><input type="text" id="addn" placeholder="после строки номер"></td>
                </tr>
            </table>
            <button id="click3" style="width: 100%">Вставить</button>
            <button id="click4" style="width: 100%">Обновить sort</button>
            <button id="click5" style="width: 100%">Обновить db_val</button>
            </p>
            <p>
                <textarea rows= "10" style="width:100%;" id="serialize2" placeholder='[0] => GSM (2G)
                            [1] => EDGE (2.9G)
                            [2] => UMTS (3G)
                            [3] => HSPA (3.5G)
                            [4] => HSPA+ (3.75G)
                            [5] => LTE (4G)
                            [6] => DC-HSPA+
                            [7] => GSM 850
                            [8] => GSM 900
                            [9] => GSM 1800
                            [10] => GSM 1900
                            [11] => TD-SCDMA'></textarea>
                <button id="click6" style="width: 100%">Заполнить таблицу вариантами. Алиасы авто в транслейт.</button>
            </p>
            <p>
                <textarea rows= "10" style="width:100%;" id="serialize3" placeholder='<select name="sp[dual_sim]" class="psi1" id="ldual_sim">
	                    <option value="" selected="selected">Не важно</option>
	                    <option value="bit_true">да</option>
	                    <option value="bit_false">нет</option>
	                    <option value="dualsim">2</option>
	                    <option value="threesim">3</option>
	                    <option value="foursim">4</option>
                    </select>'><select name="sp[dual_sim]" class="psi1" id="ldual_sim">
                        <option value="" selected="selected">Не важно</option>
                        <option value="bit_true">да</option>
                        <option value="bit_false">нет</option>
                        <option value="dualsim">2</option>
                        <option value="threesim">3</option>
                        <option value="foursim">4</option>
                    </select></textarea>
                <button id="click7" style="width:99%">Заполнить таблицу. Разбор из html select</button>
            </p>
        </div>
        <div id="panel-copy">
            <table id="table2">
                <thead>
                <tr>
                    <td>db_val</td>
                    <td>vars</td>
                    <td></td>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><input type="text"></td>
                    <td><textarea></textarea></td>
                    <td>1</td>
                </tr>
                <tr>
                    <td><input type="text"></td>
                    <td><textarea></textarea></td>
                    <td>2</td>
                </tr>
                <tr>
                    <td><input type="text"></td>
                    <td><textarea></textarea></td>
                    <td>3</td>
                </tr>
                <tr>
                    <td><input type="text"></td>
                    <td><textarea></textarea></td>
                    <td>4</td>
                </tr>
                <tr>
                    <td><input type="text"></td>
                    <td><textarea></textarea></td>
                    <td>5</td>
                </tr>
                <tr>
                    <td><input type="text"></td>
                    <td><textarea></textarea></td>
                    <td>6</td>
                </tr>
                <tr>
                    <td><input type="text"></td>
                    <td><textarea></textarea></td>
                    <td>7</td>
                </tr>
                <tr>
                    <td><input type="text"></td>
                    <td><textarea></textarea></td>
                    <td>8</td>
                </tr>
                <tr>
                    <td><input type="text"></td>
                    <td><textarea></textarea></td>
                    <td>9</td>
                </tr>
                <tr>
                    <td><input type="text"></td>
                    <td><textarea></textarea></td>
                    <td>10</td>
                </tr>
                <tr>
                    <td><input type="text"></td>
                    <td><textarea></textarea></td>
                    <td>11</td>
                </tr>
                <tr>
                    <td><input type="text"></td>
                    <td><textarea></textarea></td>
                    <td>12</td>
                </tr>
                <tr>
                    <td><input type="text"></td>
                    <td><textarea></textarea></td>
                    <td>13</td>
                </tr>
                <tr>
                    <td><input type="text"></td>
                    <td><textarea></textarea></td>
                    <td>14</td>
                </tr>
                <tr>
                    <td><input type="text"></td>
                    <td><textarea></textarea></td>
                    <td>15</td>
                </tr>
                <tr>
                    <td><input type="text"></td>
                    <td><textarea></textarea></td>
                    <td>16</td>
                </tr>
                <tr>
                    <td><input type="text"></td>
                    <td><textarea></textarea></td>
                    <td>17</td>
                </tr>
                <tr>
                    <td><input type="text"></td>
                    <td><textarea></textarea></td>
                    <td>18</td>
                </tr>
                <tr>
                    <td><input type="text"></td>
                    <td><textarea></textarea></td>
                    <td>19</td>
                </tr>
                <tr>
                    <td><input type="text"></td>
                    <td><textarea></textarea></td>
                    <td>20</td>
                </tr>
                </tbody>
            </table>
            <p>
                <button id="click21" style="width: 100%">Серилизовать</button>
            </p>
        </div>
    </div>
</div>