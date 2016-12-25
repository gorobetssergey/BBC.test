<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "{{%check_user_news}}".
 *
 * @property integer $id
 * @property integer $user
 * @property integer $news
 *
 * @property News $news0
 * @property User $user0
 */
class CheckUserNews extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%check_user_news}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user', 'news'], 'required'],
            [['user', 'news'], 'integer'],
            [['news'], 'exist', 'skipOnError' => true, 'targetClass' => News::className(), 'targetAttribute' => ['news' => 'id']],
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
            'news' => Yii::t('app', 'News'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNews0()
    {
        return $this->hasOne(News::className(), ['id' => 'news']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser0()
    {
        return $this->hasOne(User::className(), ['id' => 'user']);
    }
    public function getCountCheckNews()
    {
        return self::find()
            ->where(['user'=>Yii::$app->user->identity->id])
            ->count();
    }
    public function getProviderSelf()
    {
        $query = self::find()->with('news0')->where(['user'=>Yii::$app->user->identity->id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 25,
            ],
        ]);
        return $dataProvider;
    }
}
