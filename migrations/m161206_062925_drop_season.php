<?php

use yii\db\Migration;

class m161206_062925_drop_season extends Migration
{
    public function up()
    {
        $this->addColumn(
            'chapter',
            'manga_id',
            $this->integer()->unsigned()->notNull()->after('season_id')
        );
        $this->addColumn(
            'session_has_chapter',
            'manga_id',
            $this->integer()->unsigned()->notNull()->after('season_id')
        );
        $this->dropForeignKey('chapter_season','chapter');
        $this->dropForeignKey('season_manga','season');
        $this->dropForeignKey('session_has_chapter_season','session_has_chapter');
        $this->execute('update chapter c
            join season s using(season_id)
            set c.manga_id = s.manga_id');
        $this->execute('update session_has_chapter c
            join season s using(season_id)
            set c.manga_id = s.manga_id');
        $this->dropColumn('chapter','season_id');
        $this->dropColumn('session_has_chapter','season_id');
        $this->addForeignKey(
            'chapter_manga',
            'chapter',
            'manga_id',
            'manga',
            'manga_id',
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            'session_has_chapter_manga',
            'session_has_chapter',
            'manga_id',
            'manga',
            'manga_id',
            'CASCADE',
            'CASCADE'
        );
        $this->dropTable('season');
    }

    public function down()
    {
        $this->dropForeignKey('chapter_manga','chapter');
        $this->dropForeignKey('session_has_chapter_manga','session_has_chapter');
        $this->createTable('season',array(
            'season_id' => $this->primaryKey()->unsigned(),
            'manga_id'  => $this->integer()->notNull()->unsigned(),
            'title'     => $this->string(128)->notNull(),
            'position'  => $this->smallInteger()->notNull()->unsigned()
        ));
        $this->addColumn(
            'chapter',
            'season_id',
            $this->integer()->unsigned()->notNull()->after('manga_id'));
        $this->addColumn(
            'session_has_chapter',
            'season_id',
            $this->integer()->unsigned()->notNull()->after('manga_id')
        );
        $this->execute('update chapter c
            join season s using(manga_id)
            set c.season_id = s.season_id');
        $this->execute('update session_has_chapter c
            join season s using(manga_id)
            set c.season_id = s.season_id');
        $this->dropColumn('chapter','manga_id');
        $this->dropColumn('session_has_chapter','manga_id');
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
        $this->addForeignKey(
            'session_has_chapter_season',
            'session_has_chapter',
            'season_id',
            'season',
            'season_id',
            'CASCADE',
            'CASCADE'
        );
    }

}
