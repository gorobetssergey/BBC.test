<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$ok = Yii::$app->getSession()->getFlash('profile_ok');
$no = Yii::$app->getSession()->getFlash('profile_no');
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?php if($ok):?>
            <div class="alert alert-success alert-success" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong><?=$ok?></strong>
            </div>
        <?php endif;?>
        <?php if($no):?>
            <div class="alert alert-danger alert-danger" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong><?=$no?></strong>
            </div>
        <?php endif;?>
            <h3>Изменить пароль</h3>

        <?php $form = ActiveForm::begin();?>
        <div class="form-group field-operationssend-amount">
            <?= $form->field($password, 'passwordOld')->passwordInput()?>
            <?= $form->field($password, 'passwordNew')->passwordInput()?>
        </div>
        <div class="form-group">
            <?= Html::submitButton(Yii::t('site', 'change_profile'), ['class' => 'btn btn-primary']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>