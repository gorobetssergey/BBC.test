<?php

use yii\db\Migration;

class m161215_233202_create_table_status_news extends Migration
{
    public function safeUp()
    {
        $this->createTable('news_status',[
            'id' => $this->primaryKey(),
            'value' => $this->string(15)->notNull()
        ]);
        Yii::$app->db->createCommand()->batchInsert('news_status', ['value'], [
            ['moderation'],
            ['success'],
            ['forbidden']
        ])->execute();
    }

    public function safeDown()
    {
        $this->dropTable('news_status');
    }
}
