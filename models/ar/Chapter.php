<?php

namespace app\models\ar;

use Yii;
use \app\models\ar\origin\CChapter;


class Chapter extends CChapter
{

    const CHAPTER_GROUP_COUNT = 50;

    const LAST_CHAPTER_COUNT = 50;

    public function getTitle() {
        return $this->getRoundedNumber().($this->title ? ' - ' : '').$this->title;
    }


    protected function getRoundedNumber() {
        if ($this->number%1 > 0.01) {
            return $this->number;
        }
        return  round($this->number);

    }

    public function getUrl() {
        return array('read/view','manga'=>$this->season->manga->url,'id'=>$this->chapter_id);
    }

    /**
     * @return Chapter
     */
    public function getNextChapter() {
        return Chapter::find()->
            where(['and','season_id='.$this->season_id,'number>'.$this->number])->
            orderBy('number')->
            one();
    }

    /**
     * @return Image[][]
     */
    public function getGroupedImages() {
        /** @var Image[] $images */
        $images = $this->
            getImages()->
            orderBy(array('page'=>'asc','position'=>'asc'))->
            all();
        $result = array();
        foreach ($images as $image) {
            $result[$image->page][$image->position] = $image;
        }
        return $result;
    }

    /**
     * @return Chapter[][]
     */
    public static function getGroupedLastChapters() {
        /** @var Chapter[] $chapters */
        $chapters = self::find()->
            orderBy('created desc')->
            limit(self::LAST_CHAPTER_COUNT)->
            all();
        $result = array();
        foreach ($chapters as $chapter) {
            $date = new \DateTime($chapter->created);
            $day = $date->format('j F Y');
            $seasonId = $chapter->season_id;
            if (!isset($result[$day])) {
                $result[$day] = [];
            }
            if (!isset($result[$day][$seasonId])) {
                $result[$day][$seasonId] = [
                    'season' => $chapter->season,
                    'chapters' => [],
                ];
            }
            $result[$day][$seasonId]['chapters'][] = $chapter->getRoundedNumber();
        }
        foreach ($result as $day => $seasons) {
            foreach ($seasons as $seasonId => $season) {
                $chapters = $season['chapters'];
                sort($chapters);
                $newChapters = [];
                $lastChapter = null;
                foreach ($chapters as $chapter) {
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
                $result[$day][$seasonId]['chapters'] = $newChapters;
            }
        }
        return $result;
    }


}
