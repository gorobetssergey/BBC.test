<?php

use app\components\NewsWidget;

$ok = Yii::$app->getSession()->getFlash('news_delete_ok');
$no = Yii::$app->getSession()->getFlash('news_delete_err');
$allow = Yii::$app->getSession()->getFlash('news_allow_ok');
$allow_err = Yii::$app->getSession()->getFlash('news_allow_err');
?>
<div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
    <?php if($ok):?>
        <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <strong><?=$ok?></strong>
        </div>
    <?php endif;?>
    <?php if($no):?>
        <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <strong><?=$no?></strong>
        </div>
    <?php endif;?>
    <?php if($allow):?>
        <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <strong><?=$allow?></strong>
        </div>
    <?php endif;?>
    <?php if($allow_err):?>
        <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <strong><?=$allow_err?></strong>
        </div>
    <?php endif;?>
    <h2>Новости</h2>
    <?= NewsWidget::widget([
        'provider' => $provider,
    ])?>
</div>