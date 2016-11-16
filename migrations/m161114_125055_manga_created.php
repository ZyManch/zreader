<?php

use yii\db\Migration;

class m161114_125055_manga_created extends Migration
{
    public function up()
    {
        $this->addColumn('manga','is_finished','enum("yes","no","unknown") default "unknown" after description');
        $this->addColumn('manga','created',$this->smallInteger()->unsigned()->null()->defaultValue(null)->after('is_finished'));
        $this->addColumn('manga','finished',$this->smallInteger()->unsigned()->null()->defaultValue(null)->after('created'));
    }

    public function down()
    {
        $this->dropColumn('manga','created');
        $this->dropColumn('manga','finished');
        $this->dropColumn('manga','is_finished');
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
