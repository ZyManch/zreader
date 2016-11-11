<?php

use yii\db\Migration;

class m161023_062537_chapter_date extends Migration
{
    public function up()
    {
        $this->alterColumn('chapter','created',$this->timestamp()->notNull()->defaultExpression('current_timestamp'));
    }

    public function down()
    {
        $this->alterColumn('chapter','created',$this->timestamp()->null());
    }


}
