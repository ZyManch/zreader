<?php
/**
 * Created by PhpStorm.
 * User: ZyManch
 * Date: 14.04.2016
 * Time: 10:59
 */
class ImageGrid {

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


    /**
     * @param Point $start
     * @return Point
     */
    protected function _getNextPoint(Point $start) {
        // 6 7 8
        // 5 X 1
        // 4 3 2

        $circle = array(
            45 => $start->getNeighbor(1, -1),
            90 => $start->getNeighbor(1, 0),
            135 => $start->getNeighbor(1, 1),
            180 => $start->getNeighbor(0, 1),
            225 => $start->getNeighbor(-1, 1),
            270 => $start->getNeighbor(-1, 0),
            315 => $start->getNeighbor(-1, -1),
            360 => $start->getNeighbor(0, -1),
        );
        $lastWait = null;
        foreach ($circle as $point) {
            $isWhite = $this->isWhite($point);
            if ($isWhite) {
                $lastWait = $point;
            } else if (!is_null($lastWait)) {
                return $lastWait;
            }
        }
        return $lastWait;
    }

    protected function _loadImage($fileName) {
        if (substr($fileName, -3)!='jpg') {
            throw new Exception('Unknown file format:'.$fileName);
        }
        return imagecreatefromjpeg($fileName);
    }

    protected function _loadGrid($fileName) {
        $image = $this->_loadImage($fileName);
        $pointsFileName = substr($fileName,0,strlen($fileName)-3).'php';
        if (!file_exists($pointsFileName)) {
            $points = $this->_generateGrid($image);
            file_put_contents($pointsFileName,json_encode($points));
        }
        $this->_grid = json_decode(file_get_contents($pointsFileName), 1);
    }

    protected function _generateGrid($image) {
        $width = imagesx($image);
        $height = imagesy($image);
        $result = array_fill(-1,$width+2,array_fill(-1,$height+2,true));
        for ($y=0;$y<=$height;$y++) {
            for ($x=$width;$x>=0;$x--) {
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
        return $this->_grid[$point->x][$point->y];
    }
}