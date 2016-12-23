<?php
use yii\grid\GridView;
use yii\helpers\Url;
use yii\helpers\Html;

$ok = Yii::$app->getSession()->getFlash('user_block_ok');
$no = Yii::$app->getSession()->getFlash('user_block_err');
$ok_un = Yii::$app->getSession()->getFlash('user_unblock_ok');
$no_un = Yii::$app->getSession()->getFlash('user_unblock_err');
?>
<div class="row">
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
        <?php if($ok_un):?>
            <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong><?=$ok_un?></strong>
            </div>
        <?php endif;?>
        <?php if($no_un):?>
            <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong><?=$no_un?></strong>
            </div>
        <?php endif;?>
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
                [
                    'attribute'=>'block',
                    'content'=>function($model, $key, $index, $column){
                        return ($model->block) ? Yii::t('site','block') : Yii::t('site','no_block');
                    }
                ],
                'login_error',
                [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => 'Заблокировать',
                    'buttons' => [
                        'view' => function ($model, $key, $index) {
                            $goo = '';
                            $title = '';
                            if($key['block']){
                                $url = 'user-unblock';
                                $title = 'Разблокировать';
                            }else{
                                $url = 'user-block';
                                $title = 'Заблокировать';
                            }
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