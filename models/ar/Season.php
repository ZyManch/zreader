<?php

namespace app\models\ar;

use Yii;
use \app\models\ar\origin\CSeason;


class Season extends CSeason
{

    public function getUrl() {
        return array('manga/view','manga'=>$this->manga->url,'season_id'=>$this->season_id);
    }

}
