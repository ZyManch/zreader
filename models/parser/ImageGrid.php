<?php
/**
 * Created by PhpStorm.
 * User: ZyManch
 * Date: 14.04.2016
 * Time: 10:59
 */
namespace app\models\parser;

use app\models\ar\Image;

class ImageGrid {

    // 6 7 8
    // 5 X 1
    // 4 3 2

    const RIGHT = 0;
    const BOTTOM = 1;
    const LEFT = 2;
    const TOP = 3;

    protected $_grid = [];

    protected $_gd;
    protected $_width;
    protected $_height;

    public function __construct($fileName) {
        $this->_gd = $this->_loadImage($fileName);
    }

    public function getWidth() {
        return $this->_width;
    }

    public function getHeight() {
        return $this->_height;
    }


    /**
     * @param Point $point
     * @param $normal
     * @return Frame
     */
    public function extractFrame(Point $point) {
        $step = 0;
        $start = $point;
        $frame = new Frame();
        do {
            $point = $this->_getNextPoint($point);
            $frame->addPoint($point);
            $step++;
        } while (!$point->isEqual($start) && $step < 10000);
        return $frame;
    }

    public function markAsWhite(Frame $frame) {
        $minX = $frame->getMinX();
        $maxX = $frame->getMaxX();
        $minY = $frame->getMinY();
        $maxY = $frame->getMaxY();
        for ($x=$minX;$x<=$maxX;$x++) {
            for ($y=$minY;$y<=$maxY;$y++) {
                $a = new Point($x, $y);
                if ($frame->inFrame($a)) {
                    $this->_grid[$x][$y] = true;
                }
            }
        }
    }

    /**
     * @param Point $start
     * @return Point
     */
    protected function _getNextPoint(Point $start) {
        $circle = array(
            self::RIGHT        => $start->getNeighbor(1, 0),
            self::BOTTOM       => $start->getNeighbor(0, 1),
            self::LEFT         => $start->getNeighbor(-1, 0),
            self::TOP          => $start->getNeighbor(0, -1),
        );
        /** @var Point $point */
        for ($i=1;$i>=-2;$i--) {
            $currentDirection = ($start->direction+$i+4)%4;
            $point = $circle[$currentDirection];
            $isWhite = $this->isWhite($point);
            if ($isWhite) {
                $point->direction = $currentDirection;
                return $point;
            }
        }
    }

    protected function _loadImage($fileName) {
        if (substr($fileName, -3)!='jpg') {
            throw new \Exception('Unknown file format:'.$fileName);
        }
        $gd = imagecreatefromjpeg($fileName);
        $this->_width = imagesx($gd);
        $this->_height = imagesy($gd);
        return $gd;
    }


    protected function _isWhite($x, $y) {
        $rgb = imagecolorat($this->_gd, $x, $y);
        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF;
        return $r > 220 && $g > 220 && $b > 220;
    }

    public function isWhite(Point $point) {
        if ($point->x < 0 || $point->x > $this->_width) {
            return true;
        }
        if ($point->y < 0 || $point->y > $this->_height) {
            return true;
        }
        if (!isset($this->_grid[$point->x][$point->y])) {
            $this->_grid[$point->x][$point->y] = $this->_isWhite($point->x, $point->y);
        }
        return $this->_grid[$point->x][$point->y];

    }
}