<?php

use yii\db\Migration;

class m161213_220946_create_table_user extends Migration
{
    public function safeUp()
    {
        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'email' => $this->string(30)->notNull(),
            'verificate' => $this->boolean()->defaultValue(false),
            'password' => $this->string(255)->notNull(),
            'old_password' => $this->string(255)->notNull(),
            'token' => $this->string(255)->notNull(),//for password recovery
            'auth_key' => $this->string(255)->notNull(),//for login after registrations
            'role' => $this->integer()->defaultValue(1),
            'created' => $this->dateTime()->notNull(),
            'auth' => $this->boolean()->defaultValue(false),
            'block' => $this->boolean()->defaultValue(false),
            'login_error' => $this->integer(1)->defaultValue(0)
        ]);

        $this->createIndex(
            'idx-user_id_role',
            'user',
            'role'
        );
        $this->addForeignKey(
            'fk-user_id_role',
            'user',
            'role',
            'role',
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-user_id_role',
            'user'
        );
        $this->dropIndex(
            'idx-users_id_role',
            'user'
        );
        $this->dropTable('user');
    }
}
