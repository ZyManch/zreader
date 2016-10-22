<?php
/**
 * Created by PhpStorm.
 * User: ZyManch
 * Date: 14.04.2016
 * Time: 10:56
 */
namespace app\models;

class Frame {

    const MIN_POINTS_TO_SKIP = 400;

    const MIN_X = 'minx';
    const MAX_X = 'maxx';
    const MIN_Y = 'minY';
    const MAX_Y = 'maxY';

    /** @var Point[] */
    protected $_points = array();

    protected $_stat;

    public function addPoint(Point $point) {
        $this->_points[] = $point;
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
        return reset($this->_points);
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
            foreach ($this->getPoints() as $point) {
                if ($point->x < $minX) {
                    $minX = $point->x;
                }
                if ($point->y < $minY) {
                    $minY = $point->y;
                }
                if ($point->x > $maxX) {
                    $maxX = $point->x;
                }
                if ($point->y > $maxY) {
                    $maxY = $point->y;
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
        return ($this->getMaxX()-$this->getMinX() <= 10) ||
               ($this->getMaxY()-$this->getMinY() <= 10);
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

    public function optimize() {
        $point = end($this->_points);
        //var_dump(array_slice($frame,0,4));die();
        $result = array($point);
        $fixed = $point;
        $last = null;
        foreach ($this->_points as $point) {
            if (is_null($last)) {
                $last = $point;
                continue;
            }

            if (!$point->isOnLine($last, $fixed)) {
                $fixed = $last;
                $result[] = $last;
            }
            $last = $point;

        }
        $this->_points = $result;
        if (sizeof($this->_points) > self::MIN_POINTS_TO_SKIP) {
            $minX = $this->getMinX();
            $maxX = $this->getMaxX();
            $minY = $this->getMinY();
            $maxY = $this->getMaxY();
            $this->_points = array(
                new Point($minX,$minY),
                new Point($minX,$maxY),
                new Point($maxX,$maxY),
                new Point($maxX,$minY),
            );
        }
    }


}