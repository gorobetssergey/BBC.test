<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\News;

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
        <?php if(!$params['user']): ?>
            <?php if($params['block']):?>
                <?= Html::a('Разблокировать', ['allow-news', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
            <?php endif;?>
            <?php if($params['active'] == true || $params['new'] == true):?>
                <?= Html::a('Заблокировать', ['block-news', 'id' => $model->id], ['class' => 'btn btn-warning']) ?>
            <?php endif;?>
            <?php if($params['new'] == true):?>
                <?= Html::a('Одобрить', ['allow-news', 'id' => $model->id], ['class' => 'btn btn-info']) ?>
            <?php endif;?>
        <?php else:?>
            <?= Html::a('Удалить', ['delete-self-news', 'id' => $model->id], ['class' => 'btn btn-danger']) ?>
        <?php endif;?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'user_id',
            [
                'label'=>'Модератор',
                'value'=> $model->user->getEmail($model->moderator_id)
            ],
            'created_at',
            'forbidden_at',
            'title',
            'text',
            [
                'label'=>'Статус',
                'value'=> $model->getMyStatus($model->status)
            ],
        ],
    ]) ?>
</div>