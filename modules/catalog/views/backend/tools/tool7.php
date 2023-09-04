<?php
$bundle = \backend\themes\shop\pageAssets\catalog\tools\tool7::register($this);
?>

<script id="itemTemplate" type="text/template">
    <form action="#">
        <input name="type" type="radio" value="dictionary" <% if(type == 'dictionary') print("CHECKED"); %>> dictionary<br/>
        <input name="type" type="radio" value="number_range" <% if(type == 'number_range') print("CHECKED"); %>> number_range<br/>
        <input name="type" type="radio" value="boolean_checkbox" <% if(type == 'boolean_checkbox') print("CHECKED"); %>> boolean_checkbox
        <table border="1" style="width: 100%">
            <tbody>
            <tr>
                <td width="30">Сорт</td>
                <td width="30">Доп</td>
                <td>Общая информация</td>
                <td>Другие данные</td>
                <td width="30"></td>
            </tr>
            <tr>
                <td>
                    <input name="sort" value="<%= sort %>" title="sort"/>
                </td>
                <td>
                    <input name="hide" type="checkbox" value="<%= hide %>" <% if(hide) print('checked="checked"'); %>
                    title="Скрывать?"/>
                </td>
                <td width="300">
                    <input name="title" value="<%= title %>" placeholder="title"/><br/>
                    <input name="alias" value="<%= alias %>" placeholder="alias"/><br/>
                    <textarea name="desc" placeholder="desc" cols="40" rows="3"><%= desc %></textarea>
                </td>
                <td>
                    <div class="type-1"
                    <% if (type != 'dictionary') { %>style="display:none;"<% } %> >
                    <textarea name="vars" placeholder="vars" cols="40" rows="6"><%= vars %></textarea>
                    <textarea name="varsTop" placeholder="varsTop" cols="40" rows="6"><%= varsTop %></textarea>
                    </div>
                    <div class="type-2"
                    <% if (type != 'number_range') { %>style="display:none;"<% } %> >
                    <input name="unit" value="<%= unit %>" placeholder="unit"/><br/>
                    <input name="ratio" value="<%= ratio %>" placeholder="ratio"/>
                    </div>
                </td>
                <td>
                    <button class="delete">Delete</button>
                </td>
            </tr>
            </tbody>
        </table>
    </form>
</script>


<div id="items">
    <input id="id_category" placeholder="id_category"/>&nbsp;
    <input type="button" id="getItems" value="Получить поля"/>&nbsp;
    <input type="button" id="getManufacturers" value="Получить производителей"/>&nbsp;
    <input type="button" id="saveItems" value="Сохранить поля"/>&nbsp;
    <input type="button" id="addItem" value="Добавить поле"/><br/><br/>
</div>