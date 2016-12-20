<?php

namespace app\controllers;

use app\models\News;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\User;
use Yii;

use yii\web\Controller;
use yii\web\NotFoundHttpException;

class CabinetController extends BaseController
{
    public $layout = 'cabinet';

    public function behaviors() {
        if(Yii::$app->user->identity->role==User::ROLE_USER) {
            return [
                'access' => [
                    'class' => AccessControl::className(),
                    'rules' => [
                        [
                            'actions' => [
                                'index', 'news-add', 'news-self', 'view-news', 'update-news', 'delete-news'
                            ],
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],

            ];
        }else{
            return [
                'access' => [
                    'rules' => [
                        [
                            'actions' => [],
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
            ];
        }
    }

    public function actionIndex()
    {
        return $this->render('index');
    }
    protected function findModeNews($id)
    {
        if (($model = News::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    public function actionNewsSelf()
    {
        $model = new News();
        return $this->render('../admin/newsAll',[
            'provider' => $model->getProvider(false)
        ]);
    }
}
