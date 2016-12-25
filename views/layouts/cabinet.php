<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\models\User;
use yii\bootstrap\Modal;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'BBC-NEWS',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);

    $menuItems = [];
    if (Yii::$app->user->identity->role==User::ROLE_USER):
        $menuItems =[
            ['label' => 'Добавить новость','url' => ['/cabinet/news-add']],
            ['label' => 'Мои новости','url' => ['/cabinet/news-self']],
            ['label' => 'Настройки','url' => ['/cabinet/profile']],
        ];
        $menuItems[]=[
            'label' => 'Выйти (' . Yii::$app->user->identity->email . ')',
            'url' => ['/site/logout'],
            'linkOptions' => [
                'data-method' => 'post'
            ]
        ];
    endif;

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>
</div>
<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>
<?php
if(Yii::$app->params['checkNews'] != ''){
    Modal::begin([
        'header'=>'<h3 class="text-center">У вас есть непрочитанные новости ('.Yii::$app->params['checkNews'].')</h3>',
        'toggleButton' => [
            'id' => 'new_news',
            'class' => 'hidden'
        ],
        'size'=>'modal-lg'
    ]);
    echo Html::a(
        'Перейти к новостям',
        [\yii\helpers\Url::toRoute('check-news')],
        [
            'class'=>'btn btn-primary btn-block',
            'style'=>'color:white',
        ]
    );
    Modal::end();
$script = <<< JS
            $('#new_news').click();
JS;
        $this->registerJs($script, yii\web\View::POS_END);
}
$this->endBody()
?>
</body>
</html>
<?php $this->endPage() ?>
