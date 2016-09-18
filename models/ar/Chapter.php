<?php

namespace app\models\ar;

use Yii;
use \app\models\ar\origin\CChapter;


class Chapter extends CChapter
{

    public function getTitle() {
        return $this->number.($this->title ? ' - ' : '').$this->title;
    }

    public function getUrl() {
        return array('read/view','manga'=>$this->season->manga->url,'id'=>$this->chapter_id);
    }

}
