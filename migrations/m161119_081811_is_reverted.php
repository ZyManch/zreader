<?php

use yii\db\Migration;

class m161119_081811_is_reverted extends Migration
{
    public function up()
    {
        $this->addColumn('manga','is_reverted','enum("yes","no") default "no" after description');
    }

    public function down()
    {
        $this->dropColumn('manga','is_reverted');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
