<?php

namespace app\models\ar\Manga;

use Yii;
use \app\models\ar;

class Model extends ar\_origin\CManga{



    const IS_FINISHED_YES = 'yes';
    const IS_FINISHED_NO = 'no';
    const IS_FINISHED_UNKNOWN = 'unknown';

    const IS_REVERTED_YES = 'yes';
    const IS_REVERTED_NO = 'no';

    public function getImageUrl() {
        return '/manga/avatar/'.$this->url.'.jpg';
    }

    public function getUrl($chapters=[]) {
        return array(
            'manga/view',
            'manga'=>$this->url,
            'chapters'=>$chapters?implode('+',$chapters):null
        );
    }

    public function incrementReads() {
        $this->updateCounters(['reads'=>1]);
    }

    public function incrementViews() {
        $this->updateCounters(['views'=>1]);
    }

    public function behaviors()
    {
        return [
            \app\behaviors\Manga::className(),
        ];
    }

    /**
     * @return ar\Manga\Model[]
     */
    public static function getBestMangas() {
        return self::find()->
            orderBy('reads desc')->
            limit(self::BEST_MANGA_COUNT)->
            all();
    }

    /**
     * @param $search
     * @return ar\Manga\Model[]
     */
    public static function getMangaByWord($search) {
        $where = ['and'];
        foreach (explode(' ',$search) as $word) {
            $word = trim($word);
            if ($word) {
                $where[] = ['like','title',$word];
            }
        }
        if (sizeof($where)==1) {
            return [];
        }
        return ar\Manga\Model::find()->
            where($where)->
            limit(self::SEASRCH_MANGA_COUNT)->
            all();
    }


    public function getSeasonByTitle($title) {
        $season = ar\Season\Model::find()->
            where('manga_id='.$this->manga_id)->
            andWhere('title=:title',array(':title'=>$title))->
            one();
        if (!$season) {
            /** @var ar\Season\Model $lastSeason */
            $lastSeason = $this->getSeasons()->
                orderBy('position desc')->
                one();
            $season = new ar\Season\Model();
            $season->manga_id = $this->manga_id;
            $season->title = $title;
            $season->position = ($lastSeason ? $lastSeason->position + 1 : 1);
            if (!$season->save()) {
                throw new \Exception('Cant create season: '.implode(',',$season->getFirstErrors()));
            }
        }
        return $season;
    }
}
