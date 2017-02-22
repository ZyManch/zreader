<?php
/**
 * Created by PhpStorm.
 * User: ZyManch
 * Date: 14.04.2016
 * Time: 10:59
 */
namespace app\models\parser;

use app\models\image\Jpeg;
use app\models\image\Png;

class FrameProvider {

    // 6 7 8
    // 5 X 1
    // 4 3 2

    const RIGHT = 0;
    const BOTTOM = 1;
    const LEFT = 2;
    const TOP = 3;

    protected $_grid = [];
    /** @var \app\models\image\Image  */
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
            $point = $this->_getNextPoint($frame, $point);
            $step++;
        } while (!$point->isEqual($start) && $step < 100000);
        return $frame;
    }

    public function extractFrames($isReverted) {
        $width = $this->getWidth();
        $height = $this->getHeight();
        $frames = [];
        $yValues = range(0, $height);
        $xValues = range(0, $width);
        if (!$isReverted) {
            rsort($xValues);
        }
        foreach ($yValues as $y) {
            foreach ($xValues  as $x) {
                $frame = $this->_getFrameByXY($x, $y, $isReverted);
                if ($frame) {
                    $frames[] = $frame;
                }
            }
        }
        return $frames;
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

    public function _getFrameByXY($x, $y, $isReverted) {
        $point = new Point($x, $y);
        if ($this->isBackground($point)) {
            return null;
        }
        $point = new Point($x+($isReverted?-1:1), $y, FrameProvider::BOTTOM);
        $frame = $this->extractFrame($point);
        $this->markAsWhite($frame);
        if ($frame->isSmall()) {
            return null;
        }
        return $frame;
    }

    /**
     * @param Frame $frame
     * @param Point $start
     * @return Point
     */
    protected function _getNextPoint(Frame $frame, Point $start) {
        /** @var Point[] $circle */
        $circle = [
            self::RIGHT        => $start->getNeighbor(1, 0),
            self::BOTTOM       => $start->getNeighbor(0, 1),
            self::LEFT         => $start->getNeighbor(-1, 0),
            self::TOP          => $start->getNeighbor(0, -1),
        ];
        $directionToSide = [
            self::RIGHT        => self::LEFT,
            self::BOTTOM       => self::TOP,
            self::LEFT         => self::RIGHT,
            self::TOP          => self::BOTTOM
        ];
        /** @var Point $point */
        for ($i=1;$i>=-2;$i--) {
            $currentDirection = ($start->direction+$i+4)%4;
            $point = $circle[$currentDirection];
            $isWhite = $this->isBackground($point);
            if ($isWhite) {
                $point->direction = $currentDirection;
                return $point;
            } else {
                $config = $directionToSide[$currentDirection];
                if ($point->y == -1) {
                    print $point->x.'='.$config."\n";
                }
                $side = new Side(
                    $point->x,
                    $point->y,
                    $config
                );
                $frame->addSide($side);
                $frame->addPoint($point);
            }
        }
    }

    protected function _loadImage($fileName) {
        if (substr($fileName, -3)!='jpg') {
            throw new \Exception('Unknown file format:'.$fileName);
        }
        try {
            $gd = Jpeg::loadFile($fileName);
        } catch (\Exception $e) {
            $gd = Png::loadFile($fileName);
        }
        $this->_width = $gd->getWidth();
        $this->_height = $gd->getHeight();
        return $gd;
    }


    protected function _isBackground($x, $y) {
        $rgb = $this->_gd->getRGB($x, $y);
        return ($rgb['r'] > 220) && ($rgb['g'] > 220) && ($rgb['b'] > 220);
    }

    public function isBackground(Point $point) {
        if ($point->x < 0 || $point->x >= $this->_width) {
            return true;
        }
        if ($point->y < 0 || $point->y >= $this->_height) {
            return true;
        }
        if (!isset($this->_grid[$point->x][$point->y])) {
            $this->_grid[$point->x][$point->y] = $this->_isBackground($point->x, $point->y);
        }
        return $this->_grid[$point->x][$point->y];

    }
}