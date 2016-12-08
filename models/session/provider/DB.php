<?php
/**
 * Created by PhpStorm.
 * User: ZyManch
 * Date: 08.12.2016
 * Time: 16:07
 */
namespace app\models\session\provider;

use app\models\session;
use app\models\ar;

class DB {

    /** @var  ar\Session\Model */
    protected $_session;

    protected $_mangas = [];

    protected $_idsByStatus = [];

    /** @var session\Hash  */
    protected $_hash;

    public function __construct(session\Hash $hash) {
        $this->_hash = $hash;
    }

    public function getSessionId($createNewOnEmpty = true) {
        $session = $this->_getSession($createNewOnEmpty);
        if (!$session) {
            return null;
        }
        return $session->session_id;
    }

    public function getMangaIdsByStatus($status) {
        if (!isset($this->_idsByStatus[$status])) {
            $session = $this->_getSession(false);
            if (!$session) {
                return [];
            }
            $this->_idsByStatus[$status] = ar\SessionHasManga\Model::find()->
                where([
                    'status' => $status,
                    'session_id' => $session->session_id
                ])->
                select(['manga_id'])->
                column();
        }
        return $this->_idsByStatus[$status];
    }

    public function getMangaIdsNotReadFinished() {
        $session = $this->_getSession(false);
        if (!$session) {
            return [];
        }
        return ar\SessionHasManga\Model::find()->
            where([
                'is_read_finished' => ar\SessionHasManga\Model::READ_FINISHED_NO,
                'session_id' => $session->session_id
            ])->
            select(['manga_id'])->
            column();
    }

    public function getMangaStatus(ar\Manga\Model $manga) {
        $sessionHasManga = $this->_getSessionHasManga($manga, false);
        if (!$sessionHasManga) {
            return ar\SessionHasManga\Model::STATUS_UNKNOWN;
        }
        return $sessionHasManga->status;
    }

    public function addSessionHasManga(ar\SessionHasManga\Model $model) {
        $this->_mangas[$model->manga_id] = $model;
    }

    public function changeMangaStatus(ar\Manga\Model $manga, $status) {
        $this->_updateLastVisit();
        if ($status == ar\SessionHasManga\Model::STATUS_UNKNOWN) {
            $sessionHasManga = $this->_getSessionHasManga($manga, false);
            if ($sessionHasManga) {
                $sessionHasManga->delete();
            }
        } else {
            $sessionHasManga = $this->_getSessionHasManga($manga, true);
            $sessionHasManga->status = $status;
            if (!$sessionHasManga->save()) {
                throw new \Exception('Ошибка изменения статуса манги для юзера:' .
                                     implode(',', $sessionHasManga->getFirstErrors()));
            }
        }
    }

    public function changeMangaLastChapterNumber(ar\Manga\Model $manga, $lastChapterNumber) {
        $this->_updateLastVisit();
        $sessionHasManga = $this->_getSessionHasManga($manga, true);
        $sessionHasManga->last_chapter_number = $lastChapterNumber;
        if ($sessionHasManga->status == ar\SessionHasManga\Model::STATUS_UNKNOWN) {
            $sessionHasManga->status = ar\SessionHasManga\Model::STATUS_STARTED;
        }
        if (!$sessionHasManga->save()) {
            throw new \Exception('Ошибка изменения статуса манги для юзера:'.implode(',', $sessionHasManga->getFirstErrors()));
        }
    }

    public function getMangaLastChapterNumber(ar\Manga\Model $manga) {
        $sessionHasManga = $this->_getSessionHasManga($manga, false);
        if (!$sessionHasManga) {
            return null;
        }
        return $sessionHasManga->last_chapter_number;
    }

    protected function _updateLastVisit() {
        $session = $this->_getSession(false);
        if ($session) {
            $session->last_visit = date('Y-m-d H:i:s');
            $session->save();
        }
    }

    /**
     * @param ar\Manga\Model $manga
     * @return ar\SessionHasManga\Model
     */
    protected function _getSessionHasManga(ar\Manga\Model $manga, $createNewOnEmpty = false) {
        if (!isset($this->_mangas[$manga->manga_id])) {
            if (!$createNewOnEmpty) {
                return null;
            }
            $sessionHasManga = null;
            $session = $this->_getSession(true);
            $sessionHasManga = $session->getSessionHasMangas()->
                where(['manga_id' => $manga->manga_id])->
                one();
            if (!$sessionHasManga) {
                $sessionHasManga = new ar\SessionHasManga\Model();
                $sessionHasManga->manga_id = $manga->manga_id;
                $sessionHasManga->status = ar\SessionHasManga\Model::STATUS_UNKNOWN;
                $sessionHasManga->save();
            }
            $this->addSessionHasManga($sessionHasManga);
        }
        return $this->_mangas[$manga->manga_id];
    }

    protected function _getSession($createNewOnEmpty = false) {
        if (!$this->_session) {
            $hash = $this->_hash->getHash($createNewOnEmpty);
            if ($hash) {
                $this->_session = ar\Session\Model::find()->
                    where(['cookie_hash' => $hash])->
                    one();
            }
            if ($createNewOnEmpty && !$this->_session) {
                $this->_session = new ar\Session\Model();
                $this->_session->cookie_hash = $hash;
                if (!$this->_session->save()) {
                    throw new \Exception('Ошибка создания сессии');
                }
            }
        }
        return $this->_session;
    }

}