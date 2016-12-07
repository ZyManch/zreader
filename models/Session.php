<?php
/**
 * Created by PhpStorm.
 * User: ZyManch
 * Date: 03.12.2016
 * Time: 16:26
 */
namespace app\models;

use app\models\ar;
use \Yii\web\Cookie;

class Session {

    const COOKIE_NAME = 'manga_session';
    const COOKIE_LIFETIME_DAY = 360;

    protected $_hash;
    protected $_session;

    protected $_alreadySaved = false;

    protected $_mangas = [];

    protected $_idsByStatus = [];

    public function __construct() {
        if (\Yii::$app->user->getIsGuest()) {
            $this->_loadCookie();
        } else {
            $this->_loadFromUser();
        }

    }

    protected function _loadFromUser() {
        /** @var ar\User\Model $user */
        $user = \Yii::$app->user->getAttributes();
        $this->_session = $user->session;
        $this->_hash = $user->session->cookie_hash;
        $this->_saveToCookie();
    }

    protected function _loadCookie() {
        $this->_hash = \Yii::$app->request->cookies->getValue(self::COOKIE_NAME);
    }

    protected function _saveToCookie() {
        if (!$this->_alreadySaved) {
            $cookie = new Cookie();
            $cookie->name = self::COOKIE_NAME;
            $cookie->value = $this->_hash;
            $cookie->expire = time()+self::COOKIE_LIFETIME_DAY*3600*24;
            \Yii::$app->response->cookies->add(
                $cookie
            );
            $this->_alreadySaved = true;
        }

    }

    protected function _generateHash() {
        $this->_hash = uniqid('', true);
        $this->_saveToCookie();
    }

    public function getHash() {
        if (!$this->_hash) {
            $this->_generateHash();
        }
        return $this->_hash;
    }

    public function getSessionId($createNewOnEmpty = true) {
        $session = $this->_getSession($createNewOnEmpty);
        if (!$session) {
            return null;
        }
        return $session->session_id;
    }

    protected function _getSession($createNewOnEmpty = false) {
        if (!$this->_session) {
            $hash = $this->getHash();
            $this->_session = ar\Session\Model::find()->
                where(['cookie_hash' => $hash])->
                one();
            if (!$this->_session) {
                $this->_session = new ar\Session\Model();
                $this->_session->cookie_hash = $hash;
                if ($createNewOnEmpty && !$this->_session->save()) {
                    throw new \Exception('Ошибка создания сессии');
                }
            }
        }
        return $this->_session;
    }

    public function changeMangaStatus(ar\Manga\Model $manga, $status) {
        $this->_updateLastVisit();
        $sessionHasManga = $this->_getSessionHasManga($manga, true);
        $sessionHasManga->status = $status;
        if (!$sessionHasManga->save()) {
            throw new \Exception('Ошибка изменения статуса манги для юзера:'.implode(',', $sessionHasManga->getFirstErrors()));
        }
    }

    public function changeMangaLastChapterNumber(ar\Manga\Model $manga, $lastChapterNumber) {
        $this->_updateLastVisit();
        $sessionHasManga = $this->_getSessionHasManga($manga, true);
        $sessionHasManga->last_chapter_number = $lastChapterNumber;
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

    public function addSessionHasManga(ar\SessionHasManga\Model $model) {
        $this->_mangas[$model->manga_id] = $model;
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
            $session = $this->_getSession();
            if ($session) {
                $sessionHasManga = $session->getSessionHasMangas()->
                    where(['manga_id' => $manga->manga_id])->
                    one();
            }
            if (!$sessionHasManga) {
                $sessionHasManga = new ar\SessionHasManga\Model();
                $sessionHasManga->manga_id = $manga->manga_id;
                $sessionHasManga->status = ar\SessionHasManga\Model::STATUS_UNKNOWN;
            }
            $this->addSessionHasManga($sessionHasManga);
        }
        return $this->_mangas[$manga->manga_id];
    }

    /**
     * @param ar\Manga\Model $manga
     * @return string
     */
    public function getMangaStatus(ar\Manga\Model $manga) {
        $sessionHasManga = $this->_getSessionHasManga($manga, false);
        if (!$sessionHasManga) {
            return ar\SessionHasManga\Model::STATUS_UNKNOWN;
        }
        return $sessionHasManga->status;
    }

    /**
     * @return int[]
     */
    public function getHiddenMangaIds() {
        return $this->getMangaIdsByStatus(ar\SessionHasManga\Model::STATUS_HIDE);
    }

    /**
     * @param $status
     * @return int[]
     */
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
}