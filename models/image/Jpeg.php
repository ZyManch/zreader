<?php
/**
 * Created by PhpStorm.
 * User: ZyManch
 * Date: 09.12.2016
 * Time: 17:20
 */
namespace app\models\image;


class Jpeg extends Image {

    protected $_colors = [];

    protected function _load($fileName) {
        return @imagecreatefromjpeg($fileName);
    }

    protected function _getColor($r, $g, $b) {
        $key = $r.'-'.$g.'-'.$b;
        if (!isset($this->_colors[$key])) {
            $this->_colors[$key] = imagecolorallocate($this->_gd,$r, $g, $b);
        }
        return $this->_colors[$key];
    }

    public function save($fileName, $quality) {
        $this->_createFolderForFileName($fileName);
        imagejpeg($this->_gd,$fileName,$quality);
    }
}