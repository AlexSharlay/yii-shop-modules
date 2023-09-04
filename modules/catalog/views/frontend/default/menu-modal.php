<div class="head_rubric">
    <span class="rubric sec_akcent alpha display-block"><i></i>Каталог</span>
</div>


<nav class="sec_menu">
    <ul class="accordion catalog_list">
        <?php
        if (isset($categories) && is_array($categories)) {
            foreach ($categories as $category) {
                echo '<li class="active sec_menu_list_item">';
                if ($category['title'] == 'Распродажа магазина') {
                    echo '<a href="' . $category['url'] . '/" ' . 'id="blink6" class="active">' . '<img src="/statics/web/catalog/menu/' . $category['menu_img'] .
                        '" alt="" style="float:left;margin-top:-7px;"/><div style="margin-left:44px">' . mb_strtoupper($category['title']) . '</div></a>';
                } else {
                    echo '<a href="' . $category['url'] . '/" ' . 'class="active">' . '<img src="/statics/web/catalog/menu/' . $category['menu_img'] .
                        '" alt="" style="float:left;margin-top:-7px;"/><div style="margin-left:44px">' . mb_strtoupper($category['title']) . '</div></a>';
                }
                echo '</li>';
            }
        }
        ?>
    </ul>
</nav>
