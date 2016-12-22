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
                                'index', 'news-add', 'news-self', 'view-news', 'update-news', 'delete-self-news', 'delete-news'
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
    public function actionNewsSelf()
    {
        $model = new News();
        return $this->render('../admin/newsAll',[
            'provider' => $model->getProvider(false)
        ]);
    }
    public function actionDeleteSelfNews($id)
    {
        $model = $this->findModeNews($id);
        if($model->delete()):
            Yii::$app->getSession()->setFlash('news_delete_ok', Yii::t('site','news_delete_ok'));
            return $this->redirect(Url::toRoute('news-self'));
        else:
            Yii::$app->getSession()->setFlash('news_delete_err', Yii::t('site','news_delete_err'));
            return $this->redirect(Url::toRoute('news-self'));
        endif;
    }
}
