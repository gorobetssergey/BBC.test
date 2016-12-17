<?php

namespace app\controllers;

use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\User;
use Yii;

use yii\web\Controller;

class AdminController extends Controller
{
    public $layout = 'admin';
    public function behaviors() {
        if(Yii::$app->user->identity->role==User::ROLE_ADMIN) {
            return [
                'access' => [
                    'class' => AccessControl::className(),
                    'rules' => [
                        [
                            'actions' => [
                                'index', 'news-all', 'news-add', 'moderation', 'users'
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
        }elseif(Yii::$app->user->identity->role==User::ROLE_MODERATOR) {
            return [
                'access' => [
                    'class' => AccessControl::className(),
                    'rules' => [
                        [
                            'actions' => [
                                'index', 'news-all', 'news-add', 'moderation'
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
        }
        else{
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
    public function actionNewsAll()
    {
        return $this->render('newsAll');
    }
    public function actionNewsAdd()
    {
        return $this->render('newsAdd');
    }
    public function actionModeration()
    {
        return $this->render('moderation');
    }
    public function actionUsers()
    {
        $model = new User();
        return $this->render('users',[
            'provider' => $model->getProvider()
        ]);
    }
}
