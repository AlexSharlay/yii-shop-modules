<form method="post" action="/backend/users/default/update-discount/?id=<?=$user->id;?>">
    <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
    <div class="row">
        <div class=col-lg-6>
            <div class="panel panel-flat">
                <div class="panel-heading">
                    <h5 class="panel-title">УНП: <?=$profile->ynp;?>. Фирма: <?=$profile->firmName;?></h5>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Категория</th>
                            <th>Скидка, %</th>
                        </tr>
                        </thead>
                        <tbody>
                        <? foreach($discounts as $discount) { ?>
                            <tr <? if ($discount['discount']) echo 'class="form-group has-success has-feedback"'; else echo 'class="form-group has-warning has-feedback"';?>>
                                <td><?=$discount['title'];?></td>
                                <td><input type="text" class="form-control" name="discount[<?=$discount['id'];?>]" value="<?=$discount['discount'];?>"></td>
                            </tr>
                        <? } ?>
                        <tr>
                            <td colspan="2">
                                <button class="btn btn-default" type="submit">Применить</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</form>




