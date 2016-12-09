<?php
/**
 * Created by PhpStorm.
 * User: ZyManch
 * Date: 09.12.2016
 * Time: 17:21
 */
namespace app\models\image;


abstract class Image {

    protected $_gd;
    protected $_width;
    protected $_height;

    protected $_colorCache = [];


    public function __construct($gd = null) {
        if ($gd) {
            $this->_gd = $gd;
            $this->_initSize();
        }
    }

    /**
     * @param $fileName
     * @return Image
     */
    public static function loadFile($fileName) {
        $class = get_called_class();
        $gd = new $class();
        $gd->load($fileName);
        return $gd;
    }

    /**
     * @param $width
     * @param $height
     * @return Image
     */
    public static function create($width, $height) {
        $class = get_called_class();
        $gd = imagecreatetruecolor($width, $height);
        return new $class($gd);
    }

    protected function _initSize() {
        $this->_width = imagesx($this->_gd);
        $this->_height = imagesy($this->_gd);
    }

    public function load($fileName) {
        $this->_gd = $this->_load($fileName);
        $this->_initSize();
    }

    abstract protected function _load($fileName);

    public function getRGB($x, $y) {
        if ($x<0 || $y<0 || $x>=$this->_width || $y>=$this->_height) {
            return $this->getBgColor($y);
        }
        if (!isset($this->_colorCache[$x][$y])) {
            $this->_colorCache[$x][$y] = $this->_getRGB($x, $y);
        }
        return $this->_colorCache[$x][$y];
    }

    protected function _getRGB($x, $y) {
        $rgb = imagecolorat($this->_gd, $x, $y);
        return array_merge($this->_parseHexToRgb($rgb),['alpha'=>0]);
    }

    protected function _parseHexToRgb($rgb) {
        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF;
        return ['r'=>$r,'g'=>$g,'b'=>$b];
    }

    public function fillBg() {
        imagealphablending($this->_gd, false);
        $bg = imagecolorallocate($this->_gd, 255, 255, 255);
        //$bg = imagecolorallocatealpha($frameGd, 255, 255, 255, 127);
        //imagecolortransparent($frameGd, $bg);
        imagefill($this->_gd, 0, 0, $bg);
        //imagesavealpha($frameGd, true);
    }

    abstract protected function _getColor($r, $g, $b);
    abstract public function save($fileName, $quality);

    public function setPixel($x, $y, $color) {
        imagesetpixel($this->_gd,$x,$y, $this->_getColor($color['r'],$color['g'],$color['b']));
    }

    public function getWidth() {
        return $this->_width;
    }

    public function getHeight() {
        return $this->_height;
    }

    public function getBgColor($y) {
        return ['r'=>255,'g'=>255,'b'=>255,'alpha'=>0];
    }

    public function __destruct() {
        @imagedestroy($this->_gd);
    }

    protected function _createFolderForFileName($fileName) {
        $dirName = dirname($fileName);
        if (!file_exists($dirName)) {
            mkdir($dirName,0777,true);
        }
    }
}