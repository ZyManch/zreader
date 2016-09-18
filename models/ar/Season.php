<?php

namespace app\models\ar;

use Yii;
use \app\models\ar\origin\CSeason;


class Season extends CSeason
{

    public function getUrl() {
        return array('season/view','manga'=>$this->manga->url,'id'=>$this->season_id);
    }

}
