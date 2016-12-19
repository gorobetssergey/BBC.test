<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
    <h2>Новости</h2>
        <?= GridView::widget([
            'dataProvider' => $provider,
            'columns' => [
                'user_id',
                'moderator_id',
                'created_at',
                'forbidden_at',
                'title',
                'text',
                'status',
                ['class' => 'yii\grid\ActionColumn',
                    'header' => 'Действие',
                    'buttons' => [
                        'view' => function ($model, $key, $index) {
                            $url = Url::toRoute('view-news?id='.$key['id'].'');
                            return Html::a('Просмотреть/', $url, [
                                'title' => \Yii::t('yii', 'Просмотреть'),
                                'data-method' => 'post',
                                'data-pjax' => '0',
                            ]);
                        },
                        'update' => function ($model, $key, $index) {
                            $url = Url::toRoute('update-news?id='.$key['id'].'');
                            return Html::a('Редактировать/', $url, [
                                'title' => \Yii::t('yii', 'Редактировать'),
                                'data-method' => 'post',
                                'data-pjax' => '0',
                            ]);
                        },
                        'delete' => function ($model, $key, $index) {
                            $url = Url::toRoute('delete-news?id='.$key['id'].'');
                            return Html::a('Удалить', $url, [
                                'title' => \Yii::t('yii', 'Удалить'),
                                'data-confirm' => 'Вы действительно хотите удалить материал?',
                                'data-method' => 'post',
                                'data-pjax' => '0',
                            ]);
                        },
                    ],
                    'template' => '{view} {update} {delete}'
                ],
            ],
        ]); ?>

</div>