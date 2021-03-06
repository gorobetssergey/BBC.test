<?php

namespace app\controllers;

use app\models\CheckAdminUser;
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
            $admin = ['users', 'user-block', 'user-unblock', 'users-new', 'user-admin-check'];
            foreach ($admin as $item) {
                $action[] = $item;
            }
            $count = (new CheckAdminUser())->getAll();
            if($count > 0){
                Yii::$app->params['checkUser'] = $count;
            }
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
        if(!empty(Yii::$app->request->post("User")["repeat_password"])):
            $post = Yii::$app->request->post();
            $model->setScenario('adminregistration');
            if($model->load($post) && $model->validate()):
                if($user = $model->registration()):
                    Yii::$app->getSession()->setFlash('registration_ok', Yii::t('site', 'registration_ok'));
                    return $this->refresh();
                else:
                    Yii::$app->getSession()->setFlash('registration_err', Yii::t('site', 'registration_err'));
                endif;
            else:
                Yii::$app->getSession()->setFlash('registration_no', Yii::t('site', 'registration_no'));
            endif;
        endif;
        return $this->render('users',[
            'provider' => $model->getProvider(),
            'model' => $model
        ]);
    }
    private function findModelUser($id)
    {
        if (($model = User::findOne($id)) !== null) {
            if (User::isAdmin())
                return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function actionUserBlock($id)
    {
        $user = $this->findModelUser($id);
        if(Yii::$app->request->isPost):
            if($user->blocked()):
                Yii::$app->getSession()->setFlash('user_block_ok', Yii::t('site','user_block_ok'));
                return $this->redirect(Url::toRoute('users'));
            else:
                Yii::$app->getSession()->setFlash('user_block_err', Yii::t('site','user_block_err'));
                return $this->redirect(Url::toRoute('users'));
            endif;
        endif;

        return $this->redirect(Url::toRoute('users'));
    }

    public function actionUserUnblock($id)
    {
        $user = $this->findModelUser($id);
        if(Yii::$app->request->isPost):
            if($user->blocked(false)):
                Yii::$app->getSession()->setFlash('user_unblock_ok', Yii::t('site','user_unblock_ok'));
                return $this->redirect(Url::toRoute('users'));
            else:
                Yii::$app->getSession()->setFlash('user_unblock_err', Yii::t('site','user_unblock_err'));
                return $this->redirect(Url::toRoute('users'));
            endif;
        endif;

        return $this->redirect(Url::toRoute('users'));
    }

    public function actionUsersNew()
    {
        $model = new CheckAdminUser();
        return $this->render('usersNew',[
            'provider' => $model->getProvider(),
            'model' => $model
        ]);
    }

    public function actionUserAdminCheck($id)
    {
        $model = CheckAdminUser::findOne($id);
        if($model != null){
            if($model->delete()){
                Yii::$app->getSession()->setFlash('user_admin_ok', Yii::t('site','user_admin_ok'));
                return $this->redirect(Url::toRoute('users'));
            }else{
                Yii::$app->getSession()->setFlash('user_admin_no', Yii::t('site','user_admin_no'));
                return $this->refresh();
            }
        }
    }
}
