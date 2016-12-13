<?php

use yii\db\Migration;

class m161213_220934_create_table_role extends Migration
{
    public function safeUp()
    {
        $this->createTable('role',[
            'id' => $this->primaryKey(),
            'value' => $this->string(10)->defaultValue('user')
        ]);
        Yii::$app->db->createCommand()->batchInsert('role', ['value'], [
            ['user'],
            ['moderator'],
            ['admin']
        ])->execute();
    }

    public function safeDown()
    {
        $this->dropTable('role');
    }
}
