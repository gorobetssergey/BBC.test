<?php

namespace app\models;

use Yii;
use yii\db\Exception;
use yii\web\IdentityInterface;

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
 * @property integer $login_error
 *
 * @property Login[] $logins
 * @property Role $role0
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{

    const IS_VERIFICATE = 1;

    const ROLE_USER = 1;
    const ROLE_MODERATOR = 2;
    const ROLE_ADMIN = 3;

    const STATUS_AUTH = 1;

    public $repeat_password;

    private $_hash;
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
            [['verificate', 'role', 'auth', 'login_error'], 'integer'],
            [['created'], 'safe'],
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
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'email' => Yii::t('app', 'Email'),
            'verificate' => Yii::t('app', 'Verificate'),
            'password' => Yii::t('app', 'Password'),
            'old_password' => Yii::t('app', 'Old Password'),
            'token' => Yii::t('app', 'Token'),
            'auth_key' => Yii::t('app', 'Auth Key'),
            'role' => Yii::t('app', 'Role'),
            'created' => Yii::t('app', 'Created'),
            'auth' => Yii::t('app', 'Auth'),
            'login_error' => Yii::t('app', 'Login Error'),
            'repeat_password' => Yii::t('app', 'Repeat Password'),
        ];
    }

    public function scenarios()
    {
        return [
            'registration' => ['email','password','repeat_password'],
            'default' => parent::scenarios()
        ];
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
            'id' => $id,
            'verificate' => self::IS_VERIFICATE
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

    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    public function setPassword($password)
    {
        $this->_hash = Yii::$app->security->generatePasswordHash($password);
        $this->old_password = $this->_hash;
        $this->password = $this->_hash;
    }

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function generateToken()
    {
        $this->token = Yii::$app->security->generateRandomString().'_'.time();
    }
    
    public function registration()
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $user = new User();
            $user->email = $this->email;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            $user->generateToken();
            $user->created = date('Y-m-d H:i:s', strtotime('now'));

            if($user->save()){
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
}
