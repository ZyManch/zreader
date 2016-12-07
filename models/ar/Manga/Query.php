<?php

namespace app\models\ar\Manga;

use app\models\ar;
use app\models\Session;

class Query extends ar\_origin\CMangaQuery {

    const BEST_MANGA_COUNT = 20;

    const SEASRCH_MANGA_COUNT = 50;

    public function init() {
        parent::init();
        /** @var Session $session */
        $session = \Yii::$app->user->getSession();
        $sessionId = $session->getSessionId();
        if ($sessionId) {
            $this->with([
                'sessionHasMangas' => function(ar\SessionHasManga\Query $query) use($sessionId) {
                    $query->onCondition('session_has_manga.session_id='.$sessionId);
                }
            ]);
        }
    }

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

    /**
     * @return $this
     */
    public function favorite() {
        /** @var Session $session */
        $session = \Yii::$app->user->getSession();
        $favoritsIds = $session->getMangaIdsByStatus(ar\SessionHasManga\Model::STATUS_FAVORITE);
        if ($favoritsIds) {
            $this->andWhere('manga.manga_id in ('.implode(',',$favoritsIds).')');
        } else {
            $this->where('false');
        }
        $this->andWhere('true');
        $this->
            limit(self::BEST_MANGA_COUNT);
        return $this;
    }

    /**
     * @param $search
     * @return $this
     */
    public function search($search) {
        $where = ['and'];
        foreach (explode(' ',$search) as $word) {
            $word = trim($word);
            if ($word) {
                $where[] = ['like','title',$word];
            }
        }
        if (sizeof($where)==1) {
            $this->where('false');
        } else {
            $this->andWhere($where);
        }
        return $this->limit(self::SEASRCH_MANGA_COUNT);
    }

    public function last() {
        return $this->
            limit(self::BEST_MANGA_COUNT)->
            orderBy('changed DESC');
    }

}