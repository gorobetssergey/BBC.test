<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use app\models\News;

$this->title = 'My Yii Application';

$this->registerJs(
    '$("document").ready(function(){
        $("select").change(function(){
            if($(this).val() == 0) return false;
            $("#cb").click();
        });
    });'
);
?>

<div class="row">
    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-2" id = 'new_note'>
        <div class="notes-form">
                <?php $form = ActiveForm::begin([
                    'method' => 'post',
                    'action' => '/site/index'
            ]); ?>

                    <?= Html::dropDownList('count',[$s],$arr) ?>
                    <?= Html::submitButton('Ok', ['class' => 'hidden', 'name' => 'count-button', 'id' => 'cb']) ?>
                <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="notes">
        <?php if($models != null): ?>
            <?php foreach ($models as $model): ?>
                <div class="panel panel-default col-lg-3 col-md-4 col-sm-6 col-xs-12">
                    <div class="panel-heading"><?=News::getNewsPrewu($model['title'])?></div>
                    <div class="panel-body">
                        <?=News::getNewsPrewu($model['text'])?>
                        <hr>
                        <?php if($statususer !== true): ?>
                            <h6><?=Html::a('Для просмотра авторизируйся', Url::toRoute('login'))?></h6>
                        <?php elseif($statususer === true):?>
                            <h6><?=Html::a('Смотреть полностью', Url::toRoute('news-view?id='.$model['id']))?></h6>
                        <?php endif;?>
                    </div>
                </div>
            <?php endforeach; ?>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h3><?=\yii\widgets\LinkPager::widget(['pagination' => $pages]);?></h3>
            </div>
        <?php else:?>
            <div class="wells col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h3>Новости отсутствуют</h3>
            </div>
        <?php endif; ?>
    </div>
</div>
