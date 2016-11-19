<?php

use yii\db\Migration;

class m161118_114049_english_title extends Migration
{
    public function up()
    {
        $this->alterColumn('manga','title', $this->string(200));
        $this->alterColumn('manga','original_title', $this->string(256));
        $this->addColumn('manga','english_title', $this->string(256)->after('url'));
    }

    public function down()
    {
        $this->dropColumn('manga','english_title');
        $this->alterColumn('manga','title', $this->string(128));
        $this->alterColumn('manga','original_title', $this->string(128));
    }

}
