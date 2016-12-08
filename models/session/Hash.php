<?php
/**
 * Created by PhpStorm.
 * User: ZyManch
 * Date: 03.12.2016
 * Time: 16:26
 */
namespace app\models\session;

use app\models\ar;
use \Yii\web\Cookie;

class Hash {

    const COOKIE_NAME = 'manga_session';
    const COOKIE_LIFETIME_DAY = 360;

    protected $_hash;

    protected $_alreadySaved = false;


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

    public function getHash($createNew = true) {
        if ($createNew && !$this->_hash) {
            $this->_generateHash();
        }
        return $this->_hash;
    }

}