<?php

namespace app\models\ar\Genre;

use \app\models\ar;
use yii\data\ActiveDataProvider;


class Model extends ar\_origin\CGenre {

    public static function getAll() {
        return self::find()->
            orderBy('title')->
            all();
    }

    public function getUrl() {
        return array('genre/view','genre'=>$this->url);
    }

    public function getMangaProvider() {
        $query = ar\Manga\Model::find()->
            joinWith('mangaHasGenres')->
            where(['manga_has_genre.genre_id'=>$this->genre_id])->
            orderBy('reads DESC');
        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }

}
