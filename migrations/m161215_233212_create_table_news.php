<?php

use yii\db\Migration;

class m161215_233212_create_table_news extends Migration
{
    public function safeUp()
    {
        $this->createTable('news',[
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(11)->notNull(),
            'moderator_id' => $this->integer(11)->defaultValue(null),
            'created_at' => $this->dateTime()->notNull(),
            'forbidden_at' => $this->dateTime()->defaultValue(null),
            'title' => $this->string(255)->notNull()->unique(),
            'text' => $this->text()->notNull(),
            'status' => $this->integer(11)->notNull(),
        ]);
        $this->createIndex(
            'idx-news_user_id',
            'news',
            'user_id'
        );
        $this->addForeignKey(
            'fk-news_user_id',
            'news',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );
        $this->createIndex(
            'idx-news_news_status',
            'news',
            'status'
        );
        $this->addForeignKey(
            'fk-news_news_status',
            'news',
            'status',
            'news_status',
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-news_user_id',
            'news'
        );
        $this->dropIndex(
            'idx-news_user_id',
            'news'
        );
        $this->dropForeignKey(
            'fk-news_news_status',
            'news'
        );
        $this->dropIndex(
            'idx-news_news_status',
            'news'
        );
        $this->dropTable('news');
    }
}
