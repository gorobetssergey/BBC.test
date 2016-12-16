<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "news".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $moderator_id
 * @property string $created_at
 * @property string $forbidden_at
 * @property string $title
 * @property string $text
 * @property integer $status
 *
 * @property User $user
 */
class News extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'news';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'created_at', 'title', 'text', 'status'], 'required'],
            [['user_id', 'moderator_id', 'status'], 'integer'],
            [['created_at', 'forbidden_at'], 'safe'],
            [['text'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'moderator_id' => Yii::t('app', 'Moderator ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'forbidden_at' => Yii::t('app', 'Forbidden At'),
            'title' => Yii::t('app', 'Title'),
            'text' => Yii::t('app', 'Text'),
            'status' => Yii::t('app', 'Status'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
