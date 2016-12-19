<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$ok = Yii::$app->getSession()->getFlash('news_add_ok');
$no = Yii::$app->getSession()->getFlash('news_add_no');
?>
<div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
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
    <h2>Добавить новость</h2>
    <div class="col-lg-6 col-md-4 col-sm-12 col-xs-12">
        <?php $form = ActiveForm::begin();?>
        <div class="form-group field-operationssend-amount">
            <label class="control-label">Заглавие</label>
            <?= Html::activeTextInput($model, 'title', ['class' => 'form-control','id'=>'title']) ?>
            <?= Html::error($model, 'title', ['class' => 'error-block']) ?>
            <div class="help-block"></div>
        </div>
        <div class="form-group field-operationssend-amount">
            <label class="control-label">Текст</label>
            <?= Html::activeTextarea($model, 'text', ['class' => 'form-control','id'=>'text']) ?>
            <?= Html::error($model, 'text', ['class' => 'error-block']) ?>
            <div class="help-block"></div>
        </div>
        <div class="form-group">
            <?= Html::submitButton(Yii::t('site', 'add_news'), ['class' => 'btn btn-primary']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>