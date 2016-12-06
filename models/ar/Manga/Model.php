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


    public function getLastChapters() {
        /** @var ar\Chapter\Model[] $chapters */
        $chapters = $this->getChapters()->
            where('created>=adddate("'.$this->changed.'",interval -1 day)')->
            orderBy('number asc')->
            all();
        $result = [];
        foreach ($chapters as $chapter) {
            $result[] = $chapter->getRoundedNumber();
        }
        sort($result);
        $newChapters = [];
        $lastChapter = null;
        foreach ($result as $chapter) {
            if (is_null($lastChapter) || ($lastChapter+1 < $chapter)) {
                $newChapters[] = ['from'=>$chapter,'to'=>$chapter];
            } else {
                $newChapters[sizeof($newChapters)-1]['to'] = $chapter;
            }
            $lastChapter = $chapter;
        }
        foreach ($newChapters as $index => $range) {
            if ($range['from']==$range['to']) {
                $newChapters[$index] = $range['from'];
            } else {
                $newChapters[$index] = $range['from'].'-'.$range['to'];
            }
        }
        return $newChapters;
    }

}
