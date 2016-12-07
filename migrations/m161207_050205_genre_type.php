<?php

use yii\db\Migration;

class m161207_050205_genre_type extends Migration
{
    public function up()
    {
        $this->addColumn('genre','power',$this->smallInteger()->defaultValue(1));
        $this->addColumn('genre','genre_type_id',$this->integer()->unsigned()->notNull());
        $this->createTable('genre_type',array(
            'genre_type_id' => $this->primaryKey()->unsigned(),
            'title'    => $this->string(128)->notNull()
        ));
        $this->batchInsert('genre_type',['genre_type_id','title'],[
            [1,'Мир'],
            [2,'Жанр'],
            [3,'Отношения'],
            [4,'Возраст'],
            [5,'Прочее']
        ]);

        $this->update('genre',[
            'genre_type_id' => 1
        ],'genre_id in (4,12,14,17,18,19,21,22,27)');
        $this->update('genre',[
            'genre_type_id' => 2
        ],'genre_id in (2,3,7,8,16,20,23,24,26,32,34,35,36,37,38)');
        $this->update('genre',[
            'genre_type_id' => 3
        ],'genre_id in (5,6,11,25,40,41)');
        $this->update('genre',[
            'genre_type_id' => 4
        ],'genre_id in (9,28,29,30,31,15,33)');
        $this->update('genre',[
            'genre_type_id' => 5
        ],'genre_id in (1,10,13,39)');
        $this->addForeignKey(
            'genre_genre_type',
            'genre',
            'genre_type_id',
            'genre_type',
            'genre_type_id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function down()
    {
        $this->dropForeignKey(
            'genre_genre_type',
            'genre'
        );
        $this->dropColumn('genre','power');
        $this->dropColumn('genre','genre_type_id');
        $this->dropTable('genre_type');
    }

}
