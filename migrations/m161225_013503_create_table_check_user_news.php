<?php

use yii\db\Migration;

class m161225_013503_create_table_check_user_news extends Migration
{
    public function safeUp()
    {
        $this->createTable('check_user_news',[
            'id' => $this->primaryKey(),
            'user' => $this->integer(11)->notNull(),
            'news' => $this->integer(11)->notNull(),
        ]);

        $this->createIndex(
            'idx-news_user',
            'check_user_news',
            'user'
        );
        $this->createIndex(
            'idx-news_news',
            'check_user_news',
            'news'
        );
        $this->addForeignKey(
            'fk-news_user',
            'check_user_news',
            'user',
            'user',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-news_news',
            'check_user_news',
            'news',
            'news',
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-news_news',
            'check_user_news'
        );
        $this->dropForeignKey(
            'fk-news_user',
            'check_user_news'
        );
        $this->dropIndex(
            'idx-news_news',
            'check_user_news'
        );
        $this->dropIndex(
            'idx-news_user',
            'check_user_news'
        );
        $this->dropTable('check_user_news');
    }
}
