<?php
/**
 * Created by PhpStorm.
 * User: ZyManch
 * Date: 14.04.2016
 * Time: 10:52
 */

namespace app\models\parser;


class Side {

    const LEFT = 'left';
    const RIGHT = 'right';
    const ONLINE = 'online';

    public $x;
    public $y;

    public $side = [];

    static $_count = 0;

    public function __construct($x, $y, $side = []) {
        $this->x = $x;
        $this->y = $y;
        if (!is_array($side)) {
            $side = [$side];
        }
        $this->side = $side;
        self::$_count++;
    }


    public function isOnLine(Point $p1, Point $p2) {
        if (($p1->x == $p2->x) || ($p1->x == $this->x)) {
            return (($p1->x == $p2->x) && ($p1->x == $this->x));
        }
        return (($p1->y-$p2->y)/($p1->x-$p2->x)-($p1->y-$this->y)/($p1->x-$this->x) < 0.000001);
    }

    public function isBetween(Point $p1, Point $p2) {
        if (!$this->isOnLine($p1, $p2)) {
            return false;
        }
        if (($p1->x == $this->x) && ($p1->y == $this->y)) {
            return true;
        }
        if (($p2->x == $this->x) && ($p2->y == $this->y)) {
            return true;
        }
        if ($p1->y == $p2->y) {
            return ($this->x > $p1->x) === ($this->x < $p2->x);
        } else {
            return ($this->y > $p1->y) === ($this->y < $p2->y);
        }
    }

    public function isEqual(Point $point) {
        return $this->x == $point->x && $this->y == $point->y;
    }

    public function getNeighbor($diffX, $diffY) {
        return new Point($this->x + $diffX,$this->y+ $diffY);
    }

    public function __destruct() {
        self::$_count--;
    }
}