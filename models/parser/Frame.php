<?php
/**
 * Created by PhpStorm.
 * User: ZyManch
 * Date: 14.04.2016
 * Time: 10:56
 */
namespace app\models\parser;

class Frame {

    const MIN_POINTS_TO_SKIP = 400;

    const MIN_X = 'minx';
    const MAX_X = 'maxx';
    const MIN_Y = 'minY';
    const MAX_Y = 'maxY';

    /** @var Point[][] */
    protected $_points = array();

    protected $_stat;

    protected $_first;

    public function addPoint(Point $point) {
        if (is_null($this->_first)) {
            $this->_first = $point;
        }
        $this->_points[$point->x][$point->y] = $point;
        ksort($this->_points[$point->x]);
    }

    /**
     * @return Point[]
     */
    public function getPoints() {
        return $this->_points;
    }

    /**
     * @return Point
     */
    public function getFirst() {
        return $this->_first;
    }

    public function inFrame(Point $point) {
        if (!isset($this->_points[$point->x])) {
            return false;
        }
        $inFrame = false;
        foreach ($this->_points[$point->x] as $y => $border) {
            if ($point->y == $y) {
                return true;
            }
            if ($border->direction === ImageGrid::RIGHT) {
                $inFrame = false;
            } else {
                $inFrame = true;
            }
        }
        return $inFrame;
    }

    public function _loadStat() {
        if(!is_null($this->_stat)) {
            return;
        }
        $first = $this->getFirst();
        if (is_null($first)) {
            $this->_stat = array(
                self::MIN_X => 0,
                self::MAX_X => 0,
                self::MIN_Y => 0,
                self::MAX_Y => 0
            );
        } else {
            $minX = $first->x;
            $maxX = $first->x;
            $minY = $first->y;
            $maxY = $first->y;
            foreach ($this->_points as $x => $points) {
                if ($x < $minX) {
                    $minX = $x;
                }
                if ($x > $maxX) {
                    $maxX = $x;
                }

                foreach ($points as $y=>$point) {
                    if ($y < $minY) {
                        $minY = $y;
                    }
                    if ($y > $maxY) {
                        $maxY = $y;
                    }
                }
            }
            $this->_stat = array(
                self::MIN_X => $minX,
                self::MAX_X => $maxX,
                self::MIN_Y => $minY,
                self::MAX_Y => $maxY
            );
        }
    }

    public function isSmall() {
        return ($this->getMaxX()-$this->getMinX() <= 30) ||
               ($this->getMaxY()-$this->getMinY() <= 30);
    }

    public function getTitle() {
        return sprintf(
            '[%d:%d,%d:%d]',
            $this->getMinX(),
            $this->getMaxX(),
            $this->getMinY(),
            $this->getMaxY()
        );
    }

    public function getMinX() {
        $this->_loadStat();
        return $this->_stat[self::MIN_X];
    }

    public function getMinY() {
        $this->_loadStat();
        return $this->_stat[self::MIN_Y];
    }

    public function getMaxX() {
        $this->_loadStat();
        return $this->_stat[self::MAX_X];
    }

    public function getMaxY() {
        $this->_loadStat();
        return $this->_stat[self::MAX_Y];
    }

}