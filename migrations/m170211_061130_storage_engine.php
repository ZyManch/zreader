<?php

use yii\db\Migration;

class m170211_061130_storage_engine extends Migration
{
    public function up()
    {
        $this->createTable('storage_engine',[
            'storage_engine_id'   => $this->primaryKey()->unsigned(),
            'name'                => $this->string(256)->notNull(),
            'priority'            => $this->integer(),
        ]);
        $this->insert('storage_engine',[
            'name' => 'Удаленный',
            'priority' => 1
        ]);
        $this->insert('storage_engine',[
            'name' => 'Локальный',
            'priority' => 2
        ]);
        $this->insert('storage_engine',[
            'name' => 'Обработанный',
            'priority' => 3
        ]);
        $this->addColumn('storage','storage_engine_id', $this->integer()->unsigned()->notNull());
        $this->update('storage',['storage_engine_id'=>1]);
        $this->addForeignKey(
            'storage_storage_engine',
            'storage',
            'storage_engine_id',
            'storage_engine',
            'storage_engine_id',
            'CASCADE',
            'CASCADE'
        );
        $this->dropForeignKey('image_chapter','image');
        $this->dropIndex('image_chapter_page_position','image');
        $this->createIndex('image_chapter_page_position','image',['chapter_id','page','position'],false);
        $this->addForeignKey(
            'image_chapter',
            'image',
            'chapter_id',
            'chapter',
            'chapter_id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function down()
    {
        $this->dropForeignKey('image_chapter','image');
        $this->dropIndex('image_chapter_page_position','image');
        $this->createIndex('image_chapter_page_position','image',['chapter_id','page','position'],true);
        $this->dropForeignKey('storage_storage_engine','storage');
        $this->dropColumn('storage','storage_engine_id');
        $this->dropTable('storage_engine');
        $this->addForeignKey(
            'image_chapter',
            'image',
            'chapter_id',
            'chapter',
            'chapter_id',
            'CASCADE',
            'CASCADE'
        );
    }

}
