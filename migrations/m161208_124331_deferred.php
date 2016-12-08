<?php

use yii\db\Migration;

class m161208_124331_deferred extends Migration
{
    public function up()
    {
        $this->alterColumn('session_has_manga','status','enum("hide","favorite","started","deferred")');
    }

    public function down()
    {
        $this->alterColumn('session_has_manga','status','enum("hide","favorite","started")');
    }

}
