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
                                'index', 'news-all', 'news-add', 'moderation', 'users', 'view-news', 'update-news'
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
        $model = new News();
        return $this->render('newsAll',[
            'provider' => $model->getProvider()
        ]);
    }
    public function actionNewsAdd()
    {
        $model = new News();
        if(Yii::$app->request->isPost):
            $post = Yii::$app->request->post();
            if($model->load($post) && $model->save()):
                Yii::$app->getSession()->setFlash('news_add_ok', Yii::t('site','news_add'));
                return $this->refresh();
            else:
                Yii::$app->getSession()->setFlash('news_add_no', Yii::t('site','news_add_err'));
            endif;
        endif;
        return $this->render('newsAdd',[
            'model' => $model
        ]);
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
    protected function findModeNews($id)
    {
        if (($model = News::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    public function actionViewNews($id)
    {
        $model = $this->findModeNews($id);
        if(Yii::$app->request->isPost)
        {$post = Yii::$app->request->post();
            if($model->update()){
                Yii::$app->getSession()->setFlash('news_add_ok', Yii::t('site','news_add'));
            }else{
                Yii::$app->getSession()->setFlash('err_update', 'измените значение');
            }
        }

        return $this->render('viewNews', [
            'model' => $model,
        ]);
    }
    public function actionUpdateNews($id)
    {
        $model = $this->findModeNews($id);
        if(Yii::$app->request->isPost)
        {$post = Yii::$app->request->post();
            if($model->update()){
                Yii::$app->getSession()->setFlash('news_add_ok', Yii::t('site','news_add'));
            }else{
                Yii::$app->getSession()->setFlash('err_update', 'измените значение');
            }
        }

        return $this->render('updateNews', [
            'model' => $model,
        ]);
    }
}
