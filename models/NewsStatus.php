<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "news_status".
 *
 * @property integer $id
 * @property string $value
 */
class NewsStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'news_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['value'], 'required'],
            [['value'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'value' => Yii::t('app', 'Value'),
        ];
    }
}
