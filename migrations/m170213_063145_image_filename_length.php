<?php

use yii\db\Migration;

class m170213_063145_image_filename_length extends Migration
{
    public function up()
    {
        $this->alterColumn('image','filename',$this->string(256)->notNull());
    }

    public function down()
    {
        $this->alterColumn('image','filename',$this->string(60)->notNull());
    }

}
