<?php
/**
 * Created by PhpStorm.
 * User: ZyManch
 * Date: 14.04.2016
 * Time: 10:56
 */
namespace app\models\parser;

class Frame {

    const MIN_X = 'minx';
    const MAX_X = 'maxx';
    const MIN_Y = 'minY';
    const MAX_Y = 'maxY';

    /** @var Side[][] */
    protected $_sides = [];
    protected $_points = [];

    protected $_stat;

    protected $_first;

    static $_count = 0;

    public function __construct() {
        self::$_count++;
    }

    public function addSide(Side $side) {
        if (is_null($this->_first)) {
            $this->_first = $side;
        }
        if (isset($this->_sides[$side->x][$side->y])) {
            $this->_sides[$side->x][$side->y]->side = array_merge(
                $this->_sides[$side->x][$side->y]->side,
                $side->side
            );
        } else {
            $this->_sides[$side->x][$side->y] = $side;
            ksort($this->_sides[$side->x]);
        }
    }

    public function addPoint(Point $point) {
        $this->_points[] = $point;
    }

    /**
     * @return Side[][]
     */
    public function getSides() {
        return $this->_sides;
    }

    /**
     * @return Point[]
     */
    public function getPoints() {
        return $this->_points;
    }

    /**
     * @return Side
     */
    public function getFirst() {
        return $this->_first;
    }

    public function inFrame(Point $point) {
        if (!isset($this->_sides[$point->x])) {
            return false;
        }
        $inFrame = false;
        foreach ($this->_sides[$point->x] as $y => $border) {
            if ($point->y < $y) {
                return $inFrame;
            }
            if ($point->y == $y) {
                return true;
            }
            if (in_array(FrameProvider::BOTTOM,$border->side)) {
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
            foreach ($this->_sides as $x => $points) {
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
        return ($this->getMaxX()-$this->getMinX() <= 60) ||
               ($this->getMaxY()-$this->getMinY() <= 60);
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

    public function __destruct() {
        self::$_count--;
    }

}