<?php

namespace app\models\ar;

use Yii;
use \app\models\ar\origin\CImage;
use yii\helpers\Url;


class Image extends CImage {

    public function getFullPath() {
        return Url::to('@app').'/public'.$this->getViewPath();
    }

    public function getViewPath() {
        return '/manga/image/'.$this->chapter->season_id.'/'.$this->chapter_id.'/'.$this->filename;
    }

}
