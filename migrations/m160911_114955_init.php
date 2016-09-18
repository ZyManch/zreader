<?php

use yii\db\Migration;

class m160911_114955_init extends Migration
{
    public function up() {
        $this->createTable('user',array(
            'user_id' => $this->primaryKey()->unsigned(),
            'username' => $this->string(64)->notNull(),
            'email' => $this->string(128)->notNull(),
            'password' => $this->string(64)->notNull(),
            'created' => $this->dateTime()->defaultValue(null),
        ));
        $this->createTable('manga',array(
            'manga_id'       => $this->primaryKey()->unsigned(),
            'title'          => $this->string(128)->notNull(),
            'url'            => $this->string(128)->notNull(),
            'original_title' => $this->string(128),
            'description'    => $this->text(),
            'views'          => $this->bigInteger()->defaultValue(0)->unsigned()->notNull(),
            'reads'          => $this->bigInteger()->defaultValue(0)->unsigned()->notNull(),
        ));
        $this->createTable('chapter',array(
            'chapter_id' => $this->primaryKey()->unsigned(),
            'manga_id' => $this->integer()->unsigned()->notNull(),
            'number' => $this->integer()->unsigned()->notNull(),
            'title' => $this->string(128)->defaultValue(null),
            'created' => $this->date()->defaultValue(null)
        ));
        $this->createTable('image',array(
            'page_id' => $this->primaryKey()->unsigned(),
            'chapter_id' => $this->integer()->unsigned()->notNull(),
            'page' => $this->smallInteger()->unsigned()->notNull(),
            'position' => $this->smallInteger()->unsigned()->notNull(),
            'filename' => $this->string(64)->notNull(),
            'width' => $this->smallInteger()->unsigned()->notNull(),
            'height' => $this->smallInteger()->unsigned()->notNull(),
            'left' => $this->smallInteger()->unsigned()->notNull(),
            'top' => $this->smallInteger()->unsigned()->notNull(),
        ));
        $this->addForeignKey(
            'image_chapter',
            'image',
            'chapter_id',
            'chapter',
            'chapter_id',
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            'chapter_manga',
            'chapter',
            'manga_id',
            'manga',
            'manga_id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function down() {
        $this->dropForeignKey('image_chapter','image');
        $this->dropForeignKey('chapter_manga','chapter');
        $this->dropTable('manga');
        $this->dropTable('user');
        $this->dropTable('chapter');
        $this->dropTable('image');
    }

}
