<?php

use yii\helpers\Html;

echo 'Привет '.$user->email;
echo
    Html::a(Yii::t('site','confirm_email_text'),
        Yii::$app->urlManager->createAbsoluteUrl(
            [
                '/site/email-confirm',
                'key' => $user->auth_key
            ]
        ));