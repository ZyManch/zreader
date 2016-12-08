<?php

use yii\db\Migration;

class m161208_142017_keys extends Migration
{
    public function up()
    {
        $this->createIndex('manga_changed','manga','changed');
        $this->createIndex('manga_url','manga','url');
        $this->createIndex('chapter_manga_number','chapter',['manga_id','number']);
        $this->createIndex('session_hash','session','cookie_hash');
        $this->createIndex('session_has_manga_read_finished','session_has_manga',['session_id','is_read_finished']);
        $this->createIndex('session_has_manga_status','session_has_manga',['session_id','status']);
        $this->createIndex('session_has_manga_session_manga','session_has_manga',['session_id','manga_id']);
    }

    public function down()
    {
        $this->dropIndex('manga_changed','manga');
        $this->dropIndex('manga_url','manga');
        $this->dropIndex('chapter_manga_number','chapter');
        $this->dropIndex('session_hash','session');
        $this->dropIndex('session_has_manga_read_finished','session_has_manga');
        $this->dropIndex('session_has_manga_status','session_has_manga');
        $this->dropIndex('session_has_manga_session_manga','session_has_manga');
    }


}
