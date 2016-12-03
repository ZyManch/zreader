<?php

namespace app\models\ar\Season;

use \app\models\ar;


class Model extends ar\_origin\CSeason
{

    public function getUrl($chapters = []) {
        return array(
            'manga/view',
            'manga'=>$this->manga->url,
            'season_id'=>$this->season_id,
            'chapters'=>$chapters?implode('+',$chapters):null
        );
    }

    public function getFullTitle() {
        $seasons = $this->manga->getSeasons()->count();
        if ($seasons>1) {
            return $this->manga->title.'<br>'.$this->title;
        }
        return $this->manga->title;
    }

}
