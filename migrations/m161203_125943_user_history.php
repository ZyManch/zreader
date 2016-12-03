<?php

use yii\db\Migration;

class m161203_125943_user_history extends Migration
{
    public function up()
    {
        $this->addColumn(
            'user',
            'session_id',
            $this->integer()->unsigned()->notNull()->after('user_id')
        );
        $this->createTable('session',array(
            'session_id' => $this->primaryKey()->unsigned(),
            'cookie_hash' => $this->string(64)->notNull(),
            'created' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
            'last_visit' => $this->dateTime(),
        ));

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
            'user_session',
            'user',
            'session_id',
            'session',
            'session_id',
            'CASCADE',
            'CASCADE'
        );
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
        $this->addForeignKey(
            'session_has_manga_manga',
            'session_has_manga',
            'manga_id',
            'manga',
            'manga_id',
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            'session_has_manga_session',
            'session_has_manga',
            'session_id',
            'session',
            'session_id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function down()
    {
        $this->dropForeignKey(
            'user_session',
            'user'
        );
        $this->dropForeignKey(
            'session_has_chapter_session',
            'session_has_chapter'
        );
        $this->dropForeignKey(
            'session_has_chapter_season',
            'session_has_chapter'
        );
        $this->dropForeignKey(
            'session_has_manga_manga',
            'session_has_manga'
        );
        $this->dropForeignKey(
            'session_has_manga_session',
            'session_has_manga'
        );
        $this->dropTable('session_has_chapter');
        $this->dropTable('session_has_manga');
        $this->dropColumn('user','session_id');
    }

}
