<?php

use yii\db\Migration;

class m160911_121552_unique extends Migration
{
    public function up()
    {
        $this->createIndex('username_unique','user','username',true);
        $this->createIndex('email_unique','user','email',true);
        $this->createIndex('title_unique','manga','title',true);
        $this->createIndex('url_unique','manga','url',true);
        $this->createIndex('chapter_manga_unique','chapter',['chapter_id','manga_id'],true);
        $this->createIndex('image_chapter_page_position','image',['chapter_id','page','position'],true);
    }

    public function down()
    {
        $this->dropIndex('username_unique','user');
        $this->dropIndex('email_unique','user');
        $this->dropIndex('title_unique','manga');
        $this->dropIndex('url_unique','manga');
        $this->dropIndex('chapter_manga_unique','chapter');
        $this->dropIndex('image_chapter_page_position','image');
    }

}
