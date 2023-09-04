<?php

use yii\helpers\Html;

?>

<?php if (!empty($session['cart'])): ?>
    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <thead>
            <tr>
                <th>Фото</th>
                <th>Артикул</th>
                <th>Наименование</th>
                <th>Кол-во</th>
                <th width="140">Цена</th>
                <th width="16"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($session['cart'] as $id => $item): ?>
                <tr>
                    <td align="center"><?= Html::img("@web/statics/catalog/photo/images_small/{$item['img']}", ['alt' => $item['title'], 'height' => 50]) ?></td>
                    <td><?= $item['article'] ?></td>
                    <td><?= $item['title'] ?></td>
                    <td><?= $item['qty'] ?></td>
                    <td><?= price($item['price']) ?></td>
                    <td><span data-id="<?= $id ?>" class="glyphicon glyphicon-remove text-danger del-item" aria-hidden="true"></span></td>
                </tr>
            <?php endforeach ?>
            <tr>
                <td colspan="4">Итого:</td>
                <td colspan="2" align="right"><?= $session['cart.qty'] . ' ' . numberof($session['cart.qty'], 'товар', array('', 'а', 'ов')); ?></td>
            </tr>
            <tr>
                <td colspan="4">На сумму:</td>
                <td colspan="2" align="right"><?= price($session['cart.sum']) ?></td>
            </tr>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <h3>Корзина пуста</h3>
<?php endif; ?>