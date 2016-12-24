<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$ok = Yii::$app->getSession()->getFlash('profile_ok');
$no = Yii::$app->getSession()->getFlash('profile_no');
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h3>Настройки уведомления о новых новостях</h3>
        <h4><?=$status?></h4>
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
        <?php $form = ActiveForm::begin();?>
        <div class="form-group field-operationssend-amount">
            <?= $form->field($model, 'name[]')->radio(['label'=>Yii::t('site','news_email')])->label(false); ?>
            <?= $form->field($model, 'name[]')->radio(['label'=>Yii::t('site','news_window')])->label(false); ?>
            <?= $form->field($model, 'name[]')->radio(['label'=>Yii::t('site','news_all')])->label(false); ?>
            <?= $form->field($model, 'name[]')->radio(['label'=>Yii::t('site','news_no')])->label(false); ?>
        </div>
        <div class="form-group">
            <?= Html::submitButton(Yii::t('site', 'add_news'), ['class' => 'btn btn-primary']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>