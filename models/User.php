<?php
/**
 * Created by PhpStorm.
 * User: ZyManch
 * Date: 03.12.2016
 * Time: 16:39
 */
namespace app\models;

use yii\web;
use \app\models\ar;
use \app\models\session\Settings;

class User extends  web\User {

    protected $_user;
    protected $_session;

    /**
     * @return ar\User\Model
     */
    public function getAttributes() {
        $id=$this->getId();
        if (!$id) {
            return null;
        }
        if (!$this->_user) {
            $this->_user = ar\User\Model::findOne($id);
        }
        return $this->_user;
    }

    /**
     * @return Settings
     */
    public function getSession() {
        if (!$this->_session) {
            $this->_session = new Settings();
        }
        return $this->_session;
    }

}