<?php
/**
 * Created by PhpStorm.
 * User: ZyManch
 * Date: 09.12.2016
 * Time: 17:20
 */
namespace app\models\image;


class Png extends Image {


    protected $_colorIndex = [];

    protected $_colors = [];

    protected function _load($fileName) {
        return @imagecreatefrompng($fileName);
    }

    protected function _getRGB($x, $y) {
        $rgb = imagecolorat($this->_gd, $x, $y);
        if ($rgb === false) {
            return $this->getBgColor($y);
        }
        if ($rgb > 100000) {
            return array_merge($this->_parseHexToRgb($rgb),['alpha'=>0]);
        }
        if (isset($this->_colorIndex[$rgb])) {
            return $this->_colorIndex[$rgb];
        }
        try {
            $parts = imagecolorsforindex($this->_gd, $rgb);
            $this->_colorIndex[$rgb] = [
                'r'=>$parts['red'],
                'g'=>$parts['green'],
                'b'=>$parts['blue'],
                'alpha'=>$parts['alpha']
            ];
        } catch (\Exception $e) {
            $this->_colorIndex[$rgb] = $this->getBgColor($y);
        }
        return $this->_colorIndex[$rgb];
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
        imagepng($this->_gd,$fileName,$quality/10);
    }
}