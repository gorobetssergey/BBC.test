<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

$ok = Yii::$app->getSession()->getFlash('news_update_ok');
$no = Yii::$app->getSession()->getFlash('news_update_err');
?>
<div class="material-view">
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
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Обновить', ['update-news', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'user_id',
            'moderator_id',
            'created_at',
            'forbidden_at',
            'title',
            'text',
            [
                'label'=>'status',
                'value'=>($model->status == \app\models\News::NEWS_ACTIVE) ? Yii::t('site','news_active') : Yii::t('site','news_block')
            ],
        ],
    ]) ?>
</div>