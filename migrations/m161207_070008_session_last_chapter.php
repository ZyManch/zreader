<?php

use yii\db\Migration;

class m161207_070008_session_last_chapter extends Migration
{
    public function up()
    {
        $this->addColumn(
            'session_has_manga',
            'last_chapter_number',
            $this->decimal(5,1)->unsigned()->null()
        );
        $this->dropForeignKey('session_has_chapter_manga','session_has_chapter');
        $this->dropForeignKey('session_has_chapter_session','session_has_chapter');
        $this->dropTable('session_has_chapter');
    }

    public function down()
    {
        $this->createTable('session_has_chapter',array(
            'session_has_chapter_id' => $this->primaryKey()->unsigned(),
            'session_id' => $this->integer()->unsigned()->notNull(),
            'season_id' => $this->integer()->unsigned()->notNull(),
            'chapter_from' => $this->decimal(5,1)->unsigned()->notNull(),
            'chapter_to' => $this->decimal(5,1)->unsigned()->notNull(),
        ));

        $this->createTable('session_has_manga',array(
            'session_has_manga_id' => $this->primaryKey()->unsigned(),
            'session_id' => $this->integer()->unsigned()->notNull(),
            'manga_id' => $this->integer()->unsigned()->notNull(),
            'status' => 'enum("hide","favorite","started")',
        ));
        $this->addForeignKey(
            'session_has_chapter_session',
            'session_has_chapter',
            'session_id',
            'session',
            'session_id',
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            'session_has_chapter_season',
            'session_has_chapter',
            'season_id',
            'season',
            'season_id',
            'CASCADE',
            'CASCADE'
        );
        $this->dropColumn(
            'session_has_manga',
            'last_chapter_number'
        );
    }


}
