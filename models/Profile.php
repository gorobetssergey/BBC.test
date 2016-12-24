<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%profile}}".
 *
 * @property integer $id
 * @property integer $user
 * @property integer $email
 * @property integer $window
 * @property integer $all
 * @property integer $no
 *
 * @property User $user0
 */
class Profile extends \yii\db\ActiveRecord
{
    const NOTICE = 1;

    const STATUS = [
        1 =>'email',
        2=> 'window',
        3=>'all',
        4=>'no'];
    public $name;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%profile}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user'], 'required'],
            [['name'], 'checkName'],
            [['user', 'email', 'window', 'all', 'no'], 'integer'],
            [['user'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user' => Yii::t('app', 'User'),
            'email' => Yii::t('app', 'Email'),
            'window' => Yii::t('app', 'Window'),
            'all' => Yii::t('app', 'All'),
            'no' => Yii::t('app', 'No'),
            'name' => Yii::t('app', 'Name')
        ];
    }

    public function checkName()
    {
        $zerro = 0;
        foreach ($this->name as $item) {
            if($item == 1)$zerro++;
        }
        if($zerro!=1)
            $this->addError('name','Поле обьязательно');
        return false;
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser0()
    {
        return $this->hasOne(User::className(), ['id' => 'user']);
    }
    public function setNoticeNews($data)
    {
        $status = $data['Profile']['name'];
        foreach (self::STATUS as $k=>$v) {
            $this->$v = $status{$k};
        }
        return parent::update();
    }
    public function getStatus()
    {
        if($this->email == self::NOTICE)
            return Yii::t('site','notice_news_email');
        if($this->window == self::NOTICE)
            return Yii::t('site','notice_news_window');
        if($this->all == self::NOTICE)
            return Yii::t('site','notice_news_all');
        if($this->no == self::NOTICE)
            return Yii::t('site','notice_news_no');
    }
}
