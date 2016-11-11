<?php

namespace app\models\ar;

use Yii;
use \app\models\ar\origin\CGenre;
use \app\models\ar\Manga;
use yii\data\ActiveDataProvider;


class Genre extends CGenre {

    public static function getAll() {
        return self::find()->
            orderBy('title')->
            all();
    }

    public function getUrl() {
        return array('genre/view','genre'=>$this->url);
    }

    public function getMangaProvider() {
        $query = Manga::find()->
            joinWith('mangaHasGenres')->
            where(['manga_has_genre.genre_id'=>$this->genre_id])->
            orderBy('reads DESC');
        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }

}
