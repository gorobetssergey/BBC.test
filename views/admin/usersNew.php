<?php
use yii\grid\GridView;
use yii\helpers\Url;
use yii\helpers\Html;

$no = Yii::$app->getSession()->getFlash('user_admin_no');
?>
<div class="row">
    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
        <?php if($no):?>
            <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong><?=$no?></strong>
            </div>
        <?php endif;?>
        <h4>Пользователи</h4>

        <?= GridView::widget([
            'dataProvider' => $provider,
            'columns' => [
                [
                    'attribute'=>'email',
                    'content'=>function($model, $key, $index, $column){
                        return $model->user->email;
                    }
                ],
                [
                    'attribute'=>'data',
                    'content'=>function($model, $key, $index, $column){
                        return $model->user->created;
                    }
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => 'Подтвердить',
                    'buttons' => [
                        'view' => function ($model, $key, $index) {
                            $url = 'user-admin-check';
                            $title = 'OK';
                            $urls = Url::toRoute($url.'?id='.$key['id'].'');
                            return Html::a($title, $urls, [
                                'title' => \Yii::t('yii', $title),
                                'data-method' => 'post',
                                'data-pjax' => '0',
                            ]);
                        }
                    ],
                    'template' => '{view}',
                ],
            ],
        ]); ?>
    </div>
</div>