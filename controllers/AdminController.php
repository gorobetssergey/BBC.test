<?php

namespace app\controllers;

use app\models\News;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\User;
use Yii;

use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class AdminController extends BaseController
{
    public $layout = 'admin';
    public function behaviors() {
        $action = ['index', 'news-all', 'news-add', 'moderation', 'view-news', 'update-news', 'delete-news', 'block-news', 'allow-news'];
        if(Yii::$app->user->identity->role==User::ROLE_ADMIN) {
            $action[] = 'users';
            $action[] = 'user-block';
            return [
                'access' => [
                    'class' => AccessControl::className(),
                    'rules' => [
                        [
                            'actions' => $action,
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
                            'actions' => $action,
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
        $model = new News();
        return $this->render('newsAll',[
            'provider' => $model->getProvider(true)
        ]);
    }
    public function actionModeration()
    {
        $model = new News();
        return $this->render('moderation',[
            'provider' => $model->getProvider(true,true)
        ]);
    }
    public function actionUsers()
    {
        $model = new User();
        return $this->render('users',[
            'provider' => $model->getProvider()
        ]);
    }
    public function actionUserBlock($id)
    {
        var_dump('Изменить миграцию для блока бзера');
    }
}
