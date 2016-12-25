<?php

use yii\grid\GridView;
use yii\helpers\Url;
use yii\helpers\Html;
use app\models\News;

?>
<?= GridView::widget([
    'dataProvider' => $provider,
    'columns' => [
        [
            'attribute'=>'user_id',
            'content'=>function($model, $key, $index, $column){
                return $model->news0->user->email;
            }
        ],
        ['class' => 'yii\grid\ActionColumn',
            'header' => 'Действие',
            'buttons' => [
                'view' => function ($model, $key, $index) {
                    $url = Url::toRoute('view-news?id='.$key['id'].'');
                    return Html::a('Ппометить', $url, [
                        'title' => \Yii::t('yii', 'Ппометить'),
                        'data-method' => 'post',
                        'data-pjax' => '0',
                    ]);
                },
            ],
            'template' => '{view}'
        ],
    ],
]); ?>