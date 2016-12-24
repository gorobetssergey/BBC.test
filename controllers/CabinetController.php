<?php

namespace app\controllers;

use app\models\News;
use app\models\Profile;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\User;
use Yii;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

class CabinetController extends BaseController
{
    public $layout = 'cabinet';

    public function behaviors() {
        if(Yii::$app->user->identity->role==User::ROLE_USER) {
            if(Yii::$app->user->identity->block){
                $user = new User();
                $user->on(User::EVENT_BLOCK, ['app\controllers\SiteController', 'logout']);
                $user->userblock();
        }
            return [
                'access' => [
                    'class' => AccessControl::className(),
                    'rules' => [
                        [
                            'actions' => [
                                'index', 'news-add', 'news-self', 'view-news', 'update-news', 'delete-self-news', 'delete-news', 'profile'
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
    private function findModelProfile()
    {
        if (($model = Profile::findOne(['user' => Yii::$app->user->identity->id])) !== null) {
            if(User::isUser()):
                if($model->user == Yii::$app->user->identity->id):
                    return $model;
                endif;
            endif;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function actionProfile()
    {
        $model = $this->findModelProfile();
        if(Yii::$app->request->isPost):
            $post = Yii::$app->request->post();
            if($model->load($post) && $model->setNoticeNews($post)):
                Yii::$app->getSession()->setFlash('profile_ok', Yii::t('site','profile_ok'));
                return $this->refresh();
            else:
                Yii::$app->getSession()->setFlash('profile_no', Yii::t('site','profile_no'));
                return $this->refresh();
            endif;
        endif;
        return $this->render('profile',[
            'model' => $model,
            'status' => $model->getStatus()
        ]);
    }
}
