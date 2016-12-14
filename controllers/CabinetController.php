<?php

namespace app\controllers;

use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\User;
use Yii;

use yii\web\Controller;

class CabinetController extends Controller
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
                                'index'
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
    }

    public function actionIndex()
    {
        return $this->render('index');
    }
}
