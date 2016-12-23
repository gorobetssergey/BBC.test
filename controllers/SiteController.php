<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\User;
use yii\helpers\Url;

class SiteController extends BaseController
{
    public $layout = 'site';
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
    /**
     * Login action.
     *
     * @return string
     */
    private function afterLogin()
    {
        $role = Yii::$app->user->identity->role;
        if($role == User::ROLE_USER):
            User::auth(true);
            return $this->redirect(Url::toRoute('cabinet/index'));
        elseif ($role == User::ROLE_MODERATOR || $role == User::ROLE_ADMIN):
            User::auth(true);
            return $this->redirect(Url::toRoute('admin/news-all'));
        endif;
    }
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest):
            $this->afterLogin();
        endif;
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()):
            $this->afterLogin();
        endif;
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        User::auth(false);
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public static function logout()
    {
        Yii::$app->getResponse()->redirect(Yii::$app->getHomeUrl());
        User::auth(false);
        Yii::$app->user->logout();
    }

    public function actionRegistration()
    {
        $model = new User(['scenario' => 'registration']);

        if(Yii::$app->request->isPost):
            $post = Yii::$app->request->post();
            if($model->load($post) && $model->validate()):
                if($user = $model->registration()):
                    Yii::$app->getSession()->setFlash('registration_ok', Yii::t('site', 'registration_ok'));
                    return $this->render('registrationOk');
                endif;
            endif;
        endif;

        return $this->render('registration',[
            'model' => $model
        ]);
    }
    public function actionEmailConfirm($key)
    {
        $user = new User();
        if(!$user->validateAuthKey($key)){
            var_dump('error');
                die();
        }
        return $this->redirect(Url::toRoute('cabinet/index'));
    }
}
