<?php
/**
 * Created by PhpStorm.
 * User: ZyManch
 * Date: 14.04.2016
 * Time: 10:59
 */
namespace app\models;

class ImageGrid {

    // 6 7 8
    // 5 X 1
    // 4 3 2

    const RIGHT = 0;
    const BOTTOM = 1;
    const LEFT = 2;
    const TOP = 3;

    const BOTTOM_LEFT = 4;
    const TOP_LEFT = 6;
    const BOTTOM_RIGHT = 2;
    const TOP_RIGHT = 8;

    protected $_grid;

    public function __construct($fileName) {
        $this->_loadGrid($fileName);
    }

    public function getWidth() {
        return sizeof($this->_grid)-2;
    }

    public function getHeight() {
        return sizeof($this->_grid[0])-2;
    }


    /**
     * @param Point $point
     * @param $normal
     * @return Frame
     */
    public function extractFrame(Point $point, $normal = self::BOTTOM) {
        $step = 0;
        $start = $point;
        $frame = new Frame();
        do {
            $pointAndNormal = $this->_getNextPoint($point, $normal);
            $point = $pointAndNormal[0];
            $normal = $pointAndNormal[1];
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
        $points = $frame->getPoints();
        for ($x=$minX;$x<=$maxX;$x++) {
            for ($y=$minY;$y<=$maxY;$y++) {
                $a = new Point($x, $y);
                if (!$this->_grid[$x][$y] && $a->inPolygon($points)) {
                    $this->_grid[$x][$y] = true;
                }
            }
        }
    }

    /**
     * @param Point $start
     * @return Point[]
     */
    protected function _getNextPoint(Point $start, $direction) {
        // 6 7 8
        // 5 X 1
        // 4 3 2
        $circle = array(
            self::RIGHT        => $start->getNeighbor(1, 0),
            self::BOTTOM       => $start->getNeighbor(0, 1),
            self::LEFT         => $start->getNeighbor(-1, 0),
            self::TOP          => $start->getNeighbor(0, -1),
        );
        for ($i=1;$i>=-2;$i--) {
            $currentDirection = ($direction+$i+4)%4;
            $point = $circle[$currentDirection];
            $isWhite = $this->isWhite($point);
            if ($isWhite) {
                return array($point, $currentDirection);
            }
        }
    }

    protected function _loadImage($fileName) {
        if (substr($fileName, -3)!='jpg') {
            throw new \Exception('Unknown file format:'.$fileName);
        }
        return imagecreatefromjpeg($fileName);
    }

    protected function _loadGrid($fileName) {
        $image = $this->_loadImage($fileName);
        $pointsFileName = substr($fileName,0,strlen($fileName)-3).'php';
        if (!file_exists($pointsFileName)) {
            $points = $this->_generateGrid($image);
            file_put_contents($pointsFileName,serialize($points));
        }
        $this->_grid = unserialize(file_get_contents($pointsFileName));
    }

    protected function _generateGrid($image) {
        $width = imagesx($image);
        $height = imagesy($image);
        $result = array_fill(-1,$width+2,array_fill(-1,$height+2,true));
        for ($y=0;$y<$height;$y++) {
            for ($x=$width-1;$x>=0;$x--) {
                $result[$x][$y] = $this->_isWhite($image, $x, $y);
            }
        }
        return $result;
    }

    protected function _isWhite($image, $x, $y) {
        $rgb = imagecolorat($image, $x, $y);
        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF;
        return $r > 220 && $g > 220 && $b > 220;
    }

    public function isWhite(Point $point) {
        return isset($this->_grid[$point->x][$point->y]) ? $this->_grid[$point->x][$point->y] : true;
    }
}