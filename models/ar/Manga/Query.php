<?php

namespace app\models\ar\Manga;

use app\models\ar;
use app\models\session\Settings;
use yii\db\Expression;

class Query extends ar\_origin\CMangaQuery {

    const BEST_MANGA_COUNT = 20;

    const SEARCH_MANGA_COUNT = 50;

    public function init() {
        parent::init();
        if (\Yii::$app->request->isConsoleRequest) {
            return;
        }
        /** @var Settings $session */
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
        /** @var Settings $session */
        $session = \Yii::$app->user->getSession();
        $excludeIds = $session->getMangaIdsByStatus(ar\SessionHasManga\Model::STATUS_HIDE);
        if ($excludeIds) {
            $this->andWhere('manga.manga_id not in ('.implode(',',$excludeIds).')');
        }
        return $this;
    }

    /**
     * @return $this
     */
    public function notFinished() {
        /** @var Settings $session */
        $session = \Yii::$app->user->getSession();
        $mangaIds = $session->getMangaIdsNotReadFinished();
        if ($mangaIds) {
            $this->andWhere('manga.manga_id in ('.implode(',',$mangaIds).')');
        } else {
            $this->where('false');
        }
        return $this;
    }

    /**
     * @return $this
     */
    public function hidden() {
        /** @var Settings $session */
        $session = \Yii::$app->user->getSession();
        $excludeIds = $session->getMangaIdsByStatus(ar\SessionHasManga\Model::STATUS_HIDE);
        if ($excludeIds) {
            $this->andWhere('manga.manga_id in ('.implode(',',$excludeIds).')');
        } else {
            $this->where('false');
        }
        return $this;
    }

    /**
     * @return $this
     */
    public function deferred() {
        /** @var Settings $session */
        $session = \Yii::$app->user->getSession();
        $deferredIds = $session->getMangaIdsByStatus(ar\SessionHasManga\Model::STATUS_DEFERRED);
        if ($deferredIds) {
            $this->andWhere('manga.manga_id in ('.implode(',',$deferredIds).')');
        } else {
            $this->where('false');
        }
        return $this;
    }


    /**
     * @return $this
     */
    public function favorite() {
        /** @var Settings $session */
        $session = \Yii::$app->user->getSession();
        $favoritsIds = $session->getMangaIdsByStatus(ar\SessionHasManga\Model::STATUS_FAVORITE);
        if ($favoritsIds) {
            $this->andWhere('manga.manga_id in ('.implode(',',$favoritsIds).')');
        } else {
            $this->where('false');
        }
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
        return $this;
    }

    public function orderByLastChapter() {
        return $this->addOrderBy('changed DESC');
    }

    /**
     * @return $this
     */
    public function orderByFavorites() {
        /** @var Settings $session */
        $session = \Yii::$app->user->getSession();
        $favoritsIds = $session->getMangaIdsByStatus(ar\SessionHasManga\Model::STATUS_FAVORITE);
        if ($favoritsIds) {
            $this->addOrderBy(
                new Expression('manga_id in ('.implode(',',$favoritsIds).') DESC')
            );
        }
        return $this;
    }

    /**
     * @param bool $invert
     * @return $this
     */
    public function orderByDeferred($invert = true) {
        /** @var Settings $session */
        $session = \Yii::$app->user->getSession();
        $deferreIds = $session->getMangaIdsByStatus(ar\SessionHasManga\Model::STATUS_DEFERRED);
        if ($deferreIds) {
            $this->addOrderBy(
                new Expression('manga_id in ('.implode(',',$deferreIds).') '.($invert ? 'asc':'desc'))
            );
        }
        return $this;
    }

    /**
     * @return $this
     */
    public function orderByBest() {
        $this->addOrderBy('reads desc');
        return $this;
    }


}