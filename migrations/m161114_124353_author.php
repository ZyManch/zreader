<?php

use yii\db\Migration;

class m161114_124353_author extends Migration
{
    public function up()
    {
        $this->addColumn('manga','author_id',$this->integer()->null()->unsigned()->after('manga_id'));
        $this->createTable('author',array(
            'author_id' => $this->primaryKey()->unsigned(),
            'name' => $this->string(128)->notNull(),
            'avatar' => $this->string(128)->null(),
        ));
        $this->addForeignKey('manga_author','manga','author_id','author','author_id','NO ACTION','NO ACTION');
    }

    public function down()
    {
        $this->dropForeignKey('manga_author','manga');
        $this->dropColumn('manga','author_id');
        $this->dropTable('author');

    }

}
