<?php

use yii\db\Migration;

class m161224_102517_creta_table_profile extends Migration
{
    public function safeUp()
    {
        $this->createTable('profile',[
            'id' => $this->primaryKey(),
            'user' => $this->integer(11)->notNull(),
            'email' => $this->boolean()->defaultValue(false),
            'window' => $this->boolean()->defaultValue(false),
            'all' => $this->boolean()->defaultValue(true),
            'no' => $this->boolean()->defaultValue(false),
        ]);

        $this->createIndex(
            'idx-profile_user',
            'profile',
            'user'
        );
        $this->addForeignKey(
            'fk-profile_user',
            'profile',
            'user',
            'user',
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-profile_user',
            'profile'
        );
        $this->dropIndex(
            'idx-profile_user',
            'profile'
        );

        $this->dropTable('profile');
    }
}
