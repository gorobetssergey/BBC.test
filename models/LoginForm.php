<?php

namespace app\models;

use app\models\User;
use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
            ['username', 'checkVerificate'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function checkVerificate()
    {
        if($this->getUser()):
            if($this->_user->verificate != User::IS_VERIFICATE):
                $this->errLog($this->_user);
                $this->addError('username', 'Подтвердите свою почту');
                return false;
            endif;
            if($this->_user->block == User::BLOCK):
                $this->errLog($this->_user);
                $this->addError('username', 'Акаунт заблокирован');
                return false;
            endif;
        endif;
        return true;
    }

    private function errLog($model)
    {
        $model->on(User::EVENT_ERROR_LOGIN, [$model, 'userErorLogin']);
        $model->erorlogin();
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 60 : 0);
        }
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUserEmail($this->username);
        }
        return $this->_user;
    }
}
