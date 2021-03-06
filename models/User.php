<?php

namespace app\models;

use Yii;
use yii\db\Exception;
use yii\helpers\Url;
use yii\web\IdentityInterface;
use app\models\SendMail;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $email
 * @property integer $verificate
 * @property string $password
 * @property string $old_password
 * @property string $token
 * @property string $auth_key
 * @property integer $role
 * @property string $created
 * @property integer $auth
 * @property integer $block
 * @property integer $login_error
 *
 * @property Login[] $logins
 * @property Role $role0
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{

    const EVENT_BLOCK = 'userblock';
    const EVENT_ERROR_LOGIN = 'erorlogin';
    
    const IS_VERIFICATE = 1;
    const NO_VERIFICATE = 0;

    const ROLE_USER = 1;
    const ROLE_MODERATOR = 2;
    const ROLE_ADMIN = 3;

    const STATUS_AUTH = 1;
    const BLOCK = 1;
    const UNBLOCK = 0;

    public $repeat_password;

    private $_hash;
    private $_model;

    public $a = 15;
    public $passwordOld;
    public $passwordNew;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'password', 'old_password', 'token', 'auth_key', 'created'], 'required'],
            [['verificate', 'role', 'auth', 'block', 'login_error'], 'integer'],
            [['created','auth', 'block'], 'safe'],
            [['email'], 'string', 'max' => 30],
            [['password', 'old_password', 'token', 'auth_key'], 'string', 'max' => 255],
            [['role'], 'exist', 'skipOnError' => true, 'targetClass' => Role::className(), 'targetAttribute' => ['role' => 'id']],
            /**
             * scenarios registration
             */
            [['email', 'password', 'repeat_password'], 'required', 'on'=>'registration'],
            [['email'], 'string','max' => 30, 'on'=>'registration'],
            [['password', 'repeat_password'], 'string', 'min' => 6,'max' => 30, 'on'=>'registration'],
            [['email','auth_key'], 'unique'],
            ['email', 'email'],
            ['repeat_password', 'compare', 'compareAttribute'=>'password', 'on' => 'registration' ],
            /**
             * scenarios newpassword
             */
            [['passwordOld', 'passwordNew'], 'required', 'on' => 'newpassword'],
            [['passwordOld', 'passwordNew'], 'checNewPassword', 'on' => 'newpassword'],
            [['passwordNew'], 'string', 'min' => 6,'max' => 30, 'on'=>'newpassword'],
            /**
             * scenarios adminregistration
             */
            [['email', 'password', 'repeat_password'], 'required', 'on'=>'adminregistration'],
            [['email'], 'string','max' => 30, 'on'=>'adminregistration'],
            [['password', 'repeat_password'], 'string', 'min' => 6,'max' => 30, 'on'=>'adminregistration'],
            ['repeat_password', 'compare', 'compareAttribute'=>'password', 'on' => 'adminregistration' ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'email' => Yii::t('site', 'email'),
            'verificate' => Yii::t('app', 'Verificate'),
            'password' => Yii::t('site', 'Password'),
            'old_password' => Yii::t('app', 'Old Password'),
            'token' => Yii::t('app', 'Token'),
            'auth_key' => Yii::t('app', 'Auth Key'),
            'role' => Yii::t('app', 'Role'),
            'created' => Yii::t('app', 'Created'),
            'auth' => Yii::t('app', 'Auth'),
            'login_error' => Yii::t('app', 'Login Error'),
            'block' => Yii::t('app', 'Block'),
            'repeat_password' => Yii::t('site', 'Repeat Password'),
            'passwordOld' => Yii::t('site', 'Password Old'),
            'passwordNew' => Yii::t('site', 'Password New'),
        ];
    }

    public function scenarios()
    {
        return [
            'registration' => ['email','password','repeat_password'],
            'adminregistration' => ['email','password','repeat_password'],
            'newpassword' => ['passwordOld','passwordNew'],
            'default' => parent::scenarios()
        ];
    }

    public function checNewPassword()
    {
        if (!Yii::$app->security->validatePassword($this->passwordOld,Yii::$app->user->identity->password)) {
            $this->addError('passwordOld', 'Incorrect old password.');
            return false;
        }elseif(Yii::$app->user->identity->validatePassword($this->passwordNew)){
            $this->addError('passwordNew', 'The new password must be different from old password.');
            return false;
        }elseif(Yii::$app->security->validatePassword($this->passwordNew,Yii::$app->user->identity->old_password)){
            $this->addError('passwordNew', 'The new password must be different from All old passwords.');
            return false;
        }
        $this->_hash = Yii::$app->security->generatePasswordHash($this->passwordNew);
        $this->old_password = $this->password;
        $this->password = $this->_hash;
        if($this->update(false)){
            return true;
        }
        return false;
    }

    public function userblock()
    {
        $this->trigger(self::EVENT_BLOCK);
    }
    public function erorlogin()
    {
        $this->trigger(self::EVENT_ERROR_LOGIN);
    }
    public function afterSave($insert, $changedAttributes)
    {
        if(Yii::$app->user->identity->verificate == self::NO_VERIFICATE){
            (new SendMail())->send([
                'emailTo' => $this->email,
                'text' => Yii::t('site','confirm_email_text')
            ]);
        }elseif($this->scenario == 'newpassword' ){
            (new SendMail())->sendUpdatePassword([
                'emailTo' => $this->email,
                'text' => $this->passwordNew
            ]);
        }elseif($this->scenario == 'default' ){
            (new SendMail())->sendAdminRegister([
                'emailTo' => $this->email,
                'text' => Yii::t('site','confirm_email_text')
            ]);
        }
        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
    }
    public function userErorLogin($event)
    {
        $event->sender->updateCounters(['login_error' => 1]);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLogins()
    {
        return $this->hasMany(Login::className(), ['user' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRole0()
    {
        return $this->hasOne(Role::className(), ['id' => 'role']);
    }

    /**
     * IdentityInterface
     */

    public static function findIdentity($id)
    {
        return static::findOne([
            'id' => $id
        ]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['token' => $token]);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    private function findIdentityByAuthKey($authKey)
    {
        return static::findOne([
            'auth_key' => $authKey,
            'verificate' => self::NO_VERIFICATE
        ]);
    }

    public function validateAuthKey($authKey)
    {
        if(empty($authKey))
            return false;
        $user = $this->findIdentityByAuthKey($authKey);
        if(!$user)
            return false;

        $keyExpire = Yii::$app->params['secretKeyExpire'];
        $parts = explode('_',$authKey);
        $time = (int)end($parts);
        if (($time + $keyExpire) >= time()) {
            $user->updateAttributes([
                'verificate' => self::IS_VERIFICATE,
                'auth' => self::STATUS_AUTH
            ]);
            Yii::$app->user->login($user, 60 * 15);

            return true;
        }
        return false;
    }

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString().'_'.time();
        if($this->scenario == 'adminregistration'){
            Yii::$app->session->set('token', $this->auth_key);
        }
    }

    public function generateToken()
    {
        $this->token = Yii::$app->security->generateRandomString();
    }

    public static function findByUserEmail($useremail)
    {
        return self::findOne([
            'email' => $useremail
        ]);
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password,$this->password);
    }

    public function setPassword($password)
    {
        $this->_hash = Yii::$app->security->generatePasswordHash($password);
        $this->old_password = $this->_hash;
        $this->password = $this->_hash;
    }

    public static function isAuth()
    {
        return !Yii::$app->user->isGuest && Yii::$app->user->identityClass == 'app\models\User';
    }

    public function registration()
    {
        if($this->scenario == 'adminregistration'){
            Yii::$app->session->set('pass_admin', $this->password);
        }
        list($user, $pass) = explode("@", $this->email);
        Yii::$app->session->set('mail_registration', Yii::t('site', 'confirm_email'));
        Yii::$app->session->set('mail_registration_link', 'http://'.$pass);
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $user = new User();
            $user->email = $this->email;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            $user->generateToken();
            $user->created = date('Y-m-d H:i:s', strtotime('now'));

            if($user->save()){
                Yii::$app->db->createCommand()->batchInsert('check_admin_user', ['user_id'], [
                    [$user->id],
                ])->execute();
                $transaction->commit();
                return $user;
            }else{
                $transaction->rollBack();
                return null;
            }
        }
        catch (Exception $e){
            $transaction->rollBack();
        }
        return null;
    }

    public function getProvider()
    {
        $query = self::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 25,
            ],
        ]);
        return $dataProvider;
    }
    public static function auth($params)
    {
        $data = self::findOne(Yii::$app->user->identity->id);
        $data->auth = ($params) ? self::IS_VERIFICATE : self::NO_VERIFICATE;
        $data->update();
    }
    public static function getStatus()
    {
        $user = Yii::$app->user->identity->role;
        return ($user == self::ROLE_USER) ? false: true;
    }
    public function getEmail($id)
    {
        $user = self::findOne($id);

        return ($user) ? $user->email : null;
    }
    public static function isUser()
    {
        if(Yii::$app->user->identity->role == self::ROLE_USER)
            return true;
        return false;
    }
    public static function isAdmin()
    {
        if(Yii::$app->user->identity->role == self::ROLE_ADMIN)
            return true;
        return false;
    }
    public static function isModerator()
    {
        if(Yii::$app->user->identity->role == self::ROLE_MODERATOR)
            return true;
        return false;
    }
    public function blocked($params = true)
    {
        $this->block = ($params === true) ? self::BLOCK : self::UNBLOCK;
        if($this->update())
            return true;
        return false;
    }
}
