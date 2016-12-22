<?php
use yii\grid\GridView;
use yii\helpers\Url;
use yii\helpers\Html;

?>
<div class="row">
    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
        <?= GridView::widget([
            'dataProvider' => $provider,
            'columns' => [
                'email',
                [
                    'attribute'=>'verificate',
                    'content'=>function($model, $key, $index, $column){
                        return ($model->verificate) ? Yii::t('site','verificate') : Yii::t('site','no_verificate');
                    }
                ],
                [
                    'attribute'=>'role',
                    'content'=>function($model, $key, $index, $column){
                        return Yii::t('site',$model->role0->value);
                    }
                ],
                'created',
                [
                    'attribute'=>'auth',
                    'content'=>function($model, $key, $index, $column){
                        return ($model->auth) ? Yii::t('site','auth') : Yii::t('site','no_auth');
                    }
                ],
                'login_error',
                [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => 'Заблокировать',
                    'buttons' => [
                        'view' => function ($model, $key, $index) {
                            $url = Url::toRoute('user-block?id='.$key['id'].'');
                            return Html::a('Заблокировать', $url, [
                                'title' => \Yii::t('yii', 'Заблокировать'),
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