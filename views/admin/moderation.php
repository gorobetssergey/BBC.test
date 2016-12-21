<?php

use yii\grid\GridView;
use yii\helpers\Url;
use yii\helpers\Html;
use app\models\News;

?>
<?= GridView::widget([
    'dataProvider' => $provider,
    'rowOptions'=>function($model){
        if($model->status == News::NEWS_BLOCK){
            return ['class' => 'danger'];
        }
    },
    'columns' => [
        [
            'attribute'=>'user_id',
            'content'=>function($model, $key, $index, $column){
                return $model->user->email;
            }
        ],
        [
            'attribute'=>'moderator_id',
            'content'=>function($model, $key, $index, $column){
                return ($model->moderator_id) ? $model->user->getEmail($model->moderator_id) : 'На модерации';
            }
        ],
        'created_at',
        'forbidden_at',
        'title',
        'text',
        [
            'attribute'=>'status',
            'content'=>function($model, $key, $index, $column){
                if ($model->status == News::NEWS_NEW){
                    return Yii::t('site','news_new');
                }else{
                    return Yii::t('site','news_block');
                }
            }
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => 'Одобрить',
            'buttons' => [
                'view' => function ($model, $key, $index) {
                    $url = Url::toRoute('allow-news?id='.$key['id'].'');
                    return Html::a('Одобрить', $url, [
                        'title' => \Yii::t('yii', 'Одобрить'),
                        'data-method' => 'post',
                        'data-pjax' => '0',
                    ]);
                }
            ],
            'template' => '{view}',
        ],
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
                    $url = Url::toRoute('block-news?id='.$key['id'].'');
                    return Html::a('Заблокировать/', $url, [
                        'title' => \Yii::t('yii', 'Заблокировать'),
                        'data-method' => 'post',
                        'data-pjax' => '0',
                    ]);
                },
                'delete' => function ($model, $key, $index) {
                    $url = Url::toRoute('delete-news?id='.$key['id'].'');
                    return Html::a('Удалить', $url, [
                        'title' => \Yii::t('yii', 'Удалить'),
                        'data-confirm' => 'Вы действительно хотите удалить новость?',
                        'data-method' => 'post',
                        'data-pjax' => '0',
                    ]);
                },
            ],
            'template' => '{view} {update} {delete}'
        ],
    ],
]); ?>