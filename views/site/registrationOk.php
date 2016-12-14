<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form ActiveForm */

$result = Yii::$app->getSession()->getFlash('registration_ok');
?>

<div class="level level-grey level-b-70">
    <div class="container">
        <?php if($result):?>
            <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3><?=$result?></h3>
                <p>
                    <?=
                        Html::a(
                            Yii::$app->session->get('mail_registration'),
                            Yii::$app->session->get('mail_registration_link')
                        )
                    ?>
                </p>
            </div>
        <?php endif;?>
    </div>
</div>