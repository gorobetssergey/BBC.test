<?php
use yii\grid\GridView;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$ok_admin = Yii::$app->getSession()->getFlash('user_admin_ok');

$ok = Yii::$app->getSession()->getFlash('user_block_ok');
$no = Yii::$app->getSession()->getFlash('user_block_err');
$ok_un = Yii::$app->getSession()->getFlash('user_unblock_ok');
$no_un = Yii::$app->getSession()->getFlash('user_unblock_err');
$ok_reg = Yii::$app->getSession()->getFlash('registration_ok');
$no_reg = Yii::$app->getSession()->getFlash('registration_err');
$no_reg_err = Yii::$app->getSession()->getFlash('registration_no');
$res_ok = '';
$style = '';
$arr = [$ok,$no,$ok_un,$no_un,$ok_reg,$no_reg,$no_reg_err,$ok_admin];
$style_arr = ['alert-success' ,'alert-danger', 'alert-success', 'alert-danger' ,'alert-success' ,'alert-danger', 'alert-danger', 'alert-success'];
for($i = 0; $i<count($arr);$i++) {
    if($arr{$i}){
        $res_ok = $arr{$i};
        $style = $style_arr[$i];
        break;
    }
}
?>
<div class="row">
    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
        <?php if($res_ok):?>
            <div class="alert <?=$style?> alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong><?=$res_ok?></strong>
            </div>
        <?php endif;?>
        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingOne">
                    <h4 class="panel-title">
                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            <h4>Добавить пользователя</h4>
                        </a>
                    </h4>
                </div>
                <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                    <div class="panel-body">
                        <?php $form = ActiveForm::begin(); ?>

                        <?= $form->field($model, 'email')->textInput() ?>

                        <?= $form->field($model, 'password')->passwordInput() ?>

                        <?= $form->field($model, 'repeat_password')->passwordInput() ?>

                        <?= Html::submitButton('Добавитть', ['class' => 'btn btn-primary']) ?>

                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
        <h4>Пользователи</h4>

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
                    'header' => 'Действие',
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