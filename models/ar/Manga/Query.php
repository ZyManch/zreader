<?php

namespace app\models\ar\Manga;

use app\models\ar;
use app\models\Session;

class Query extends ar\_origin\CMangaQuery {

    const BEST_MANGA_COUNT = 20;

    const SEASRCH_MANGA_COUNT = 50;

    /**
     * @return $this
     */
    public function excludeHidden() {
        /** @var Session $session */
        $session = \Yii::$app->user->getSession();
        $excludeIds = $session->getHiddenMangaIds();
        if ($excludeIds) {
            $this->andWhere('manga.manga_id not in ('.implode(',',$excludeIds).')');
        }
        return $this;
    }

    /**
     * @return $this
     */
    public function best() {
        $this->
            limit(self::BEST_MANGA_COUNT)->
            orderBy('reads desc');
        return $this;
    }

}