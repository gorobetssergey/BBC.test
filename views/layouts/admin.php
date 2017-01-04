<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AdminAsset;
use app\models\User;

AdminAsset::register($this);
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
    $menuItems =[
        ['label' => 'Новости','url' => ['/admin/news-all']],
        ['label' => 'Добавить новость','url' => ['/admin/news-add']],
        ['label' => 'Модерация','url' => ['/admin/moderation']]
    ];
    if (Yii::$app->user->identity->role==User::ROLE_ADMIN):
        $menuItems[] =['label' => 'Пользователи','url' => ['/admin/users']];
        if(Yii::$app->params['checkUser'] > 0)
            $menuItems[] =['label' => 'Новые Пользователи('.Yii::$app->params['checkUser'].')','url' => ['/admin/users-new']];
    endif;
    $menuItems[]=[
        'label' => 'Выйти (' . Yii::$app->user->identity->email . ')',
        'url' => ['/site/logout'],
        'linkOptions' => ['data-method' => 'post'],
    ];
    $menuItems[] = [
        'label' => '(статус - '. Yii::t('site', Yii::$app->user->identity->role0->value).' )'
    ];


    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
