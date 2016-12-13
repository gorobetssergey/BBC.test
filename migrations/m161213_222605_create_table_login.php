<?php

use yii\db\Migration;

class m161213_222605_create_table_login extends Migration
{
    public function safeUp()
    {
        $this->createTable('login',[
            'id' => $this->primaryKey(),
            'user' => $this->integer(11)->notNull(),
            'ip' => $this->string(15)->notNull()
        ]);
        $this->createIndex(
            'idx-login_user',
            'login',
            'user'
        );
        $this->addForeignKey(
            'fk-login_user',
            'login',
            'user',
            'user',
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-login_user',
            'login'
        );
        $this->dropIndex(
            'idx-login_user',
            'login'
        );

        $this->dropTable('login');
    }
}
