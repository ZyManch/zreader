<?php

namespace app\models\ar;

use Yii;
use \app\models\ar\origin\CChapter;


class Chapter extends CChapter
{

    const CHAPTER_GROUP_COUNT = 50;

    public function getTitle() {
        return $this->number.($this->title ? ' - ' : '').$this->title;
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

}
