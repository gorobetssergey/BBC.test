<?php
/**
 * Created by PhpStorm.
 * User: Alpha3
 * Date: 14.12.2016
 * Time: 14:39
 */

namespace app\models;


use yii\base\Model;
use Yii;
use app\models\User;

class SendMail extends Model
{
    public $email;

    public function rules()
    {
        return [
            ['email','email'],
            ['email','exist','targetClass' => User::className(),
                'filter' => [
                    'verificate' => User::NO_VERIFICATE
                ],
                'on' => 'registration',
                'message' => 'email error'
            ],
            ['email','exist','targetClass' => User::className(),'on' => 'newsmoderation','message' => 'email error']
        ];
    }

    public function scenarios()
    {
        return [
            'registration' => ['email'],
            'newsmoderation' => ['email'],
            'default' => parent::scenarios()
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => Yii::t('site', 'email'),
        ];
    }

    private function getUserNoverificate()
    {
        return User::findOne([
            'email' => $this->email,
            'verificate' => User::NO_VERIFICATE
        ]);
    }

    public function send(array $params)
    {
        $this->scenario = 'registration';
        $this->email = $params['emailTo'];
        $user = $this->getUserNoverificate();
        if($this->validate()):
            return Yii::$app->mailer->compose('emailConfirm',['user' => $user])
                ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
                ->setTo($this->email)
                ->setSubject(Yii::t('site','confirm_email'))
                ->send();
        endif;
    }

    public function sendUpdatePassword(array $params)
    {
        return Yii::$app->mailer->compose('updatepassword',['user' => $params['emailTo'],'password'=> $params['text']])
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
            ->setTo($params['emailTo'])
            ->setSubject('Измененине пароля')
            ->send();
    }
    public function sendAdminRegister(array $params)
    {
        return Yii::$app->mailer->compose('userAdminRegistration',['user' => $params['emailTo']])
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
            ->setTo($params['emailTo'])
            ->setSubject(Yii::t('site','confirm_email'))
            ->send();
    }

    public function sendAll(array $params)
    {
        $messages = [];
        $users = Profile::find()
            ->joinWith('user0')
            ->where(['user.role' => User::ROLE_USER])
            ->andWhere(['or', ['profile.email' => Profile::NOTICE],['all' => Profile::NOTICE]])
            ->all();
        foreach ($users as $user) {
            $messages[] = Yii::$app->mailer->compose('newsModeration',['id' => $params['id'],'user' => $user->user0->email])
                ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
                ->setSubject(Yii::t('site','add_new_news').' "'.$params['title'].'"')
                ->setTo($user->user0->email);
        }
        if($this->validate()) {
            return Yii::$app->mailer->sendMultiple($messages);
        }else{
            var_dump($this->errors);die();
        }
    }
}