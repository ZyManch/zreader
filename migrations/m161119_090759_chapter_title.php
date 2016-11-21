<?php

use yii\db\Migration;

class m161119_090759_chapter_title extends Migration
{
    public function up()
    {
        $this->alterColumn('chapter','title', $this->string(250)->defaultValue(null));
    }

    public function down()
    {
        $this->alterColumn('chapter','title', $this->string(128)->defaultValue(null));
    }

}
