<?php

use yii\db\Migration;

class m161118_130947_manga_has_author extends Migration
{
    public function up()
    {
        $this->dropForeignKey('manga_author','manga');
        $this->dropColumn('manga','author_id');
        $this->createTable('manga_has_author',array(
            'manga_has_author_id' => $this->primaryKey()->unsigned(),
            'manga_id'  => $this->integer()->notNull()->unsigned(),
            'author_id'  => $this->integer()->notNull()->unsigned(),
        ));

        $this->addForeignKey(
            'manga_has_author_manga',
            'manga_has_author',
            'manga_id',
            'manga',
            'manga_id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'manga_has_author_author',
            'manga_has_author',
            'author_id',
            'author',
            'author_id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function down()
    {
        $this->dropForeignKey('manga_has_author_manga','manga_has_author');
        $this->dropForeignKey('manga_has_author_author','manga_has_author');
        $this->dropTable('manga_has_author');
        $this->addColumn('manga','author_id',$this->integer()->null()->unsigned()->after('manga_id'));
        $this->addForeignKey('manga_author','manga','author_id','author','author_id','NO ACTION','NO ACTION');
    }

}
