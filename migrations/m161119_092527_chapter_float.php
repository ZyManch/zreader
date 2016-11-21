<?php

use yii\db\Migration;

class m161119_092527_chapter_float extends Migration
{
    public function up()
    {
        $this->alterColumn('chapter','number',$this->decimal(5,1)->unsigned()->notNull());
    }

    public function down()
    {
        $this->alterColumn('chapter','number',$this->integer()->unsigned()->notNull());
    }

}
