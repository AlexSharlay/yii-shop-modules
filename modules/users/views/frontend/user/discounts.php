<div class="row">
    <div class="col-md-9 col-lg-6">
        <? if ($discounts['count']) { ?>
        <div class="panel panel-flat">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Категория</th>
                        <th>Скидка, %</th>
                    </tr>
                    </thead>
                    <tbody>
                        <? foreach ($discounts['discounts'] as $discount) { ?>
                            <? if ($discount['discount']) { ?>
                            <tr <? if ($discount['discount']) echo 'class="form-group has-success has-feedback"'; else echo 'class="form-group has-warning has-feedback"'; ?>>
                                <td><?= $discount['title']; ?></td>
                                <td><?= $discount['discount']; ?></td>
                            </tr>
                            <? } ?>
                        <? } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <? } ?>
    </div>
    <div class="hidden-md col-lg-4">
    </div>
    <div class="col-md-3 col-lg-2">
        <?= $this->render('_menu') ?>
    </div>
</div>