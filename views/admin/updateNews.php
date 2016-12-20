<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;


?>
<div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
    <h2><?=($model->isNewRecord) ?  Yii::t('site', 'add_news') : Yii::t('site', 'update_news')?></h2>
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
            <?= Html::submitButton(($model->isNewRecord) ?  Yii::t('site', 'add_news') : Yii::t('site', 'update_news') , ['class' => 'btn btn-primary']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>