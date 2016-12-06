<?php

use yii\db\Migration;

class m161206_072638_task_drop_season extends Migration
{
    public function up()
    {
        $this->dropForeignKey('task_season','task');
        $this->dropColumn('task','season_id');
    }

    public function down()
    {
        $this->addColumn('task','season_id',$this->integer()->unsigned()->after('manga_id'));
        $this->addForeignKey(
            'task_season',
            'task',
            'season_id',
            'season',
            'season_id',
            'CASCADE',
            'CASCADE'
        );
    }


}
