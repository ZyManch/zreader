<?php

namespace app\models\ar;

use Yii;
use \app\models\ar\origin\CManga;

class Manga extends CManga{

    const BEST_MANGA_COUNT = 20;
    const SEASRCH_MANGA_COUNT = 50;

    const IS_FINISHED_YES = 'yes';
    const IS_FINISHED_NO = 'no';
    const IS_FINISHED_UNKNOWN = 'unknown';

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

    /**
     * @return Manga[]
     */
    public static function getBestMangas() {
        return self::find()->
            orderBy('reads desc')->
            limit(self::BEST_MANGA_COUNT)->
            all();
    }

    /**
     * @param $search
     * @return Manga[]
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
        return Manga::find()->
            where($where)->
            limit(self::SEASRCH_MANGA_COUNT)->
            all();
    }

}
