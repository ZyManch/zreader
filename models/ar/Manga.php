<?php

namespace app\models\ar;

use Yii;
use \app\models\ar\origin\CManga;

class Manga extends CManga{


    public function getImageUrl() {
        return '/manga/avatar/'.$this->url.'.jpg';
    }

    public function getUrl() {
        return array('manga/view','manga'=>$this->url);
    }
}
