<?php

use yii\db\Migration;

class m161022_133906_genre extends Migration
{
    public function up()
    {
        $this->createTable('genre',array(
            'genre_id' => $this->primaryKey()->unsigned(),
            'title'    => $this->string(128)->notNull(),
            'url'      => $this->string(128)->notNull(),
        ));

        $this->createTable('manga_has_genre',array(
            'manga_has_genre_id' => $this->primaryKey()->unsigned(),
            'manga_id'  => $this->integer()->notNull()->unsigned(),
            'genre_id'  => $this->integer()->notNull()->unsigned(),
        ));

        $this->addForeignKey(
            'manga_has_genre_manga',
            'manga_has_genre',
            'manga_id',
            'manga',
            'manga_id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'manga_has_genre_genre',
            'manga_has_genre',
            'genre_id',
            'genre',
            'genre_id',
            'CASCADE',
            'CASCADE'
        );

        $this->batchInsert('genre',
            ['title','url'],
            [
                ['арт','art'],
                ['боевик','feature'],
                ['боевые искусства','battles'],
                ['вампиры','vampires'],
                ['гарем','harem'],
                ['гендерная интрига','genderaffair'],
                ['героическое фэнтези','heroicfantasy'],
                ['детектив','detective'],
                ['дзёсэй','josei'],
                ['додзинси','doujinshi'],
                ['драма','drama'],
                ['игра','game'],
                ['история','history'],
                ['киберпанк','cyberpunk'],
                ['кодомо','kodomo'],
                ['комедия','comedy'],
                ['махо-сёдзё','maho-sedze'],
                ['меха','meha'],
                ['мистика','mystic'],
                ['научная фантастика','science'],
                ['повседневность','daily'],
                ['постапокалиптика','postapokaliptika'],
                ['приключения','adventure'],
                ['психология','psychology'],
                ['романтика','romance'],
                ['самурайский боевик','samurai'],
                ['сверхъестественное','supernatural'],
                ['сёдзё','shoujo'],
                ['сёдзё-ай','shoujo-ai'],
                ['сёнэн','syonen'],
                ['сёнэн-ай','syonen-ai'],
                ['спорт','sport'],
                ['сэйнэн','seinen'],
                ['трагедия','tragedy'],
                ['триллер','thriller'],
                ['ужасы','horror'],
                ['фантастика','fiction'],
                ['фэнтези','fantasy'],
                ['школа','school'],
                ['этти','etty'],
                ['юри','yuri'],
            ]
        );
    }

    public function down()
    {
        $this->dropForeignKey('manga_has_genre_manga','manga_has_genre');
        $this->dropForeignKey('manga_has_genre_genre','manga_has_genre');
        $this->dropTable('manga_has_genre');
        $this->dropTable('genre');
    }


}
