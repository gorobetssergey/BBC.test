<?php

use yii\db\Migration;

class m170104_150426_create_table_check_admin_user extends Migration
{
    public function safeUp()
    {
        $this->createTable('check_admin_user',[
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(11)->notNull(),
        ]);

        $this->createIndex(
            'idx-check_admin_user',
            'check_admin_user',
            'user_id'
        );
        $this->addForeignKey(
            'fk-check_admin_user',
            'check_admin_user',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-check_admin_user',
            'check_admin_user'
        );
        $this->dropIndex(
            'idx-check_admin_user',
            'check_admin_user'
        );
        $this->dropTable('check_admin_user');
    }
}
