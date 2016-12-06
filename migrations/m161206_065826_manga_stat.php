<?php

use yii\db\Migration;

class m161206_065826_manga_stat extends Migration
{
    public function up()
    {
        $this->addColumn(
            'manga',
            'chapters',
            $this->integer()->defaultValue(0)->unsigned()->notNull()->after('finished')
        );
        $this->addColumn(
            'manga',
            'changed',
            $this->dateTime()->notNull()->after('finished')
        );
        $this->addColumn(
            'session_has_manga',
            'is_read_finished',
            'enum("yes","no") default "no"'
        );
    }

    public function down()
    {
        $this->dropColumn(
            'manga',
            'chapters'
        );
        $this->dropColumn(
            'manga',
            'changed'
        );
        $this->dropColumn(
            'session_has_manga',
            'is_read_finished'
        );
    }


}
