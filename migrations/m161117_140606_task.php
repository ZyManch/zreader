<?php

use yii\db\Migration;

class m161117_140606_task extends Migration
{
    public function up()
    {
        $this->createTable('task',array(
            'task_id' => $this->primaryKey()->unsigned(),
            'manga_id' => $this->integer()->unsigned(),
            'season_id' => $this->integer()->unsigned(),
            'chapter_id' => $this->integer()->unsigned(),
            'task' => $this->string(16)->notNull(),
            'filename' => $this->string(256)->notNull(),
            'status' => 'enum("success","wait","progress","error") default "wait"',
        ));
        $this->addForeignKey(
            'task_manga',
            'task',
            'manga_id',
            'manga',
            'manga_id',
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            'task_season',
            'task',
            'season_id',
            'season',
            'season_id',
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            'task_chapter',
            'task',
            'chapter_id',
            'chapter',
            'chapter_id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function down()
    {
        $this->dropForeignKey('task_manga','task');
        $this->dropForeignKey('task_season','task');
        $this->dropForeignKey('task_chapter','task');
        $this->dropTable('task');
    }

}
