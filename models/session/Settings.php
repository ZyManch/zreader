<?php
/**
 * Created by PhpStorm.
 * User: ZyManch
 * Date: 03.12.2016
 * Time: 16:26
 */
namespace app\models\session;

use app\models\ar;

class Settings {

    /** @var Hash  */
    protected $_hash;
    /** @var provider\DB */
    protected $_session;

    protected $_idsByStatus = [];

    public function __construct() {
        $this->_hash = new Hash();
        $this->_session = new provider\DB($this->_hash);
    }

    public function isHasSettings() {
        if (\Yii::$app->request->isConsoleRequest) {
            return false;
        }
        return (bool)$this->_session->getSessionId(false);
    }

    public function getSessionId($createNewOnEmpty = true) {
        return $this->_session->getSessionId($createNewOnEmpty);
    }

    public function changeMangaStatus(ar\Manga\Model $manga, $status) {
        $this->_session->changeMangaStatus($manga, $status);
    }

    public function changeMangaLastChapterNumber(ar\Manga\Model $manga, $lastChapterNumber) {
        $this->_session->changeMangaLastChapterNumber($manga, $lastChapterNumber);
    }

    public function getMangaLastChapterNumber(ar\Manga\Model $manga) {
        return $this->_session->getMangaLastChapterNumber($manga);
    }

    public function addSessionHasManga(ar\SessionHasManga\Model $model) {
        $this->_session->addSessionHasManga($model);
    }


    /**
     * @param ar\Manga\Model $manga
     * @return string
     */
    public function getMangaStatus(ar\Manga\Model $manga) {
        return $this->_session->getMangaStatus($manga);
    }

    /**
     * @param $status
     * @return int[]
     */
    public function getMangaIdsByStatus($status) {
        return $this->_session->getMangaIdsByStatus($status);
    }

    public function getMangaIdsNotReadFinished() {
        return $this->_session->getMangaIdsNotReadFinished();
    }
}