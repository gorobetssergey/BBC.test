<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

$result1 = Yii::$app->getSession()->getFlash('upd_mat_ok');
?>
<div class="material-view">
    <?php if($result1):?>
        <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <strong><?=$result1?></strong>
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
            'status',
        ],
    ]) ?>
</div>