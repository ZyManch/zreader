<?php

use yii\db\Migration;

class m160911_122706_season extends Migration
{
    public function up()
    {
        $this->createTable('season',array(
            'season_id' => $this->primaryKey()->unsigned(),
            'manga_id'  => $this->integer()->notNull()->unsigned(),
            'title'     => $this->string(128)->notNull(),
            'position'  => $this->smallInteger()->notNull()->unsigned()
        ));
        $this->renameColumn('chapter','manga_id','season_id');
        $this->dropForeignKey('chapter_manga','chapter');
        $this->addForeignKey(
            'chapter_season',
            'chapter',
            'season_id',
            'season',
            'season_id',
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            'season_manga',
            'season',
            'manga_id',
            'manga',
            'manga_id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function down()
    {
        $this->dropForeignKey('chapter_season','chapter');
        $this->dropForeignKey('season_manga','season');
        $this->renameColumn('chapter','season_id','manga_id')   ;
        $this->dropTable('season');
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

}
