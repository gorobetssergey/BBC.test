<?php

use yii\helpers\Html;

?>

    <h3>Привет <?=$user?></h3>
    <p>Ваш пароль: <h2><?=Yii::$app->session->get('pass_admin')?></h2></p>
<?php
Html::a(Yii::t('site','confirm_email_text'),
    Yii::$app->urlManager->createAbsoluteUrl(
        [
            '/site/email-confirm',
            'key' => Yii::$app->session->get('token')
        ]
    ));