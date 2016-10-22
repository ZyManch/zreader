<?php

use yii\db\Migration;

class m161022_055701_imageType extends Migration
{
    public function up()
    {
        $this->addColumn('image','type','enum("slide","author","title") default "slide" after filename');
    }

    public function down()
    {
        $this->dropColumn('image','type');
    }


}
