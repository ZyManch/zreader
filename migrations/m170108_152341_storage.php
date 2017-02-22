<?php

use yii\db\Migration;

class m170108_152341_storage extends Migration
{
    public function up()
    {
        $this->createTable('storage',[
            'storage_id'     => $this->primaryKey()->unsigned(),
            'path'           => $this->string(256)->notNull(),
            'url'            => $this->string(128)->notNull(),
        ]);
        $this->insert('storage',[
            'storage_id' => 1,
            'path' => 'D:/Sites/reader.dev/public/manga/',
            'url' => 'http://reader.dev/manga/'
        ]);
        $this->addColumn(
            'image',
            'storage_id',
            $this->integer()->unsigned()->notNull()->after('position')
        );
        $this->execute('update image set storage_id=1');
        $this->addColumn(
            'task',
            'storage_id',
            $this->integer()->unsigned()->notNull()->after('task')
        );
        $this->execute('update task set storage_id=1');
        $this->addForeignKey(
            'image_storage',
            'image',
            'storage_id',
            'storage',
            'storage_id',
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            'task_storage',
            'task',
            'storage_id',
            'storage',
            'storage_id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function down()
    {
        $this->dropForeignKey('image_storage','image');
        $this->dropForeignKey('task_storage','task');
        $this->dropTable('storage');
        $this->dropColumn('image','storage_id');
        $this->dropColumn('task','storage_id');
    }

}
