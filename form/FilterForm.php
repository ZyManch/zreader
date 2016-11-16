<?php

namespace app\form;

use Yii;
use yii\base\Model;
use app\models\ar\Manga;
use yii\data\ActiveDataProvider;

/**
 * ContactForm is the model behind the contact form.
 */
class FilterForm extends Model
{
    const YEAR_FROM = 1990;

    const SORT_VIEWS = 'views';
    const SORT_CREATED = 'created';
    const SORT_CHAPTERS = 'chapters';

    public $genres = array();
    public $declined_genres = array();
    public $author_id;
    public $is_finished = Manga::IS_FINISHED_UNKNOWN;
    public $year_from;
    public $year_to;

    public $sort = self::SORT_VIEWS;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['genres','declined_genres'], 'each','rule'=>['integer']],
            ['author_id', 'integer'],
            ['is_finished', 'in','range'=>[Manga::IS_FINISHED_UNKNOWN,Manga::IS_FINISHED_YES,Manga::IS_FINISHED_NO]],
            [['year_from','year_to'], 'integer','min'=>self::YEAR_FROM,'max'=>date('Y')],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'genres' => 'Жанры',
            'declined_genres' => 'Исключающие жанры',
            'author_id' => 'Автор',
            'is_finished' => 'Выпуск',
            'year_from' => 'Выпуск с года',
            'year_to' => 'Выпуск до года',
        ];
    }

    public function getProvider() {
        $query = Manga::find()->
            //joinWith('seasons')->
            //joinWith('seasons.chapters')->
            joinWith('mangaHasGenres')->
            groupBy('manga.manga_id');
        foreach ($this->genres as $genreId) {
            $query->andHaving('find_in_set('.intval($genreId).',group_concat(manga_has_genre.genre_id))>0');
        }
        foreach ($this->declined_genres as $genreId) {
            $query->andHaving('find_in_set('.intval($genreId).',group_concat(manga_has_genre.genre_id))=0');
        }
        if ($this->author_id) {
            $query->andWhere('manga.author_id='.intval($this->author_id));
        }
        if ($this->is_finished == Manga::IS_FINISHED_YES) {
            $query->andWhere('manga.is_finished="'.Manga::IS_FINISHED_YES.'"');
        } else if ($this->is_finished == Manga::IS_FINISHED_NO) {
            $query->andWhere('manga.is_finished="'.Manga::IS_FINISHED_NO.'"');
        }
        if ($this->year_from) {
            $query->andWhere('manga.created>='.intval($this->year_from));
        }
        if ($this->year_to) {
            $query->andWhere('manga.created<='.intval($this->year_to));
        }
        switch ($this->sort) {
            case self::SORT_VIEWS:
                $query->orderBy('manga.views desc');
                break;
            case self::SORT_CREATED:
                $query->orderBy('manga.created desc');
                break;

        }
        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }
}
