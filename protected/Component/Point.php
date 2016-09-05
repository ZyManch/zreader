<?php
/**
 * Created by PhpStorm.
 * User: ZyManch
 * Date: 14.04.2016
 * Time: 10:52
 */
class Point {

    public $x;
    public $y;

    public function __construct($x, $y) {
        $this->x = $x;
        $this->y = $y;
    }


    public function isOnLine(Point $p1, Point $p2) {
        if (($p1->x == $p2->x) || ($p1->x == $this->x)) {
            return (($p1->x == $p2->x) && ($p1->x == $this->x));
        }
        return (($p1->y-$p2->y)/($p1->x-$p2->x)-($p1->y-$this->y)/($p1->x-$this->x) < 0.000001);
    }

    public function isEqual(Point $point) {
        return $this->x == $point->x && $this->y == $point->y;
    }

    public function getNeighbor($diffX, $diffY) {
        return new Point($this->x + $diffX,$this->y+ $diffY);
    }

}