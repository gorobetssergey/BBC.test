<?php

use yii\helpers\Html;

echo 'Привет '.$user;
echo
Html::a(Yii::t('site','new_news'),
    Yii::$app->urlManager->createAbsoluteUrl(
        [
            '/site/news',
            'id' => $id
        ]
    ));