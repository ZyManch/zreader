<?php
/**
 * Created by PhpStorm.
 * User: ZyManch
 * Date: 14.04.2016
 * Time: 10:52
 */

namespace app\models;


class Point {

    const LEFT = 'left';
    const RIGHT = 'right';
    const ONLINE = 'online';

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

    public function classify(Point $p0, Point $p1) {
        $ax = $p1->x - $p0->x;
        $ay = $p1->y - $p0->y;
        $bx = $this->x - $p0->x;
        $by = $this->y - $p0->y;
        $sa = $ax * $by - $bx * $ay;
        if ($sa > 0.0) {
            return self::LEFT;
        }
        if ($sa < 0.0) {
            return self::RIGHT;
        }
        return self::ONLINE;
    }

    function inPolygon(&$points) {
        $size = sizeof($points);
        if ($size <= 1) {
            return false;
        }

        $intersectionsCount = 0;
        $last = $size - 1;
        $lastIsUnder = $points[$last]->y < $this->y;

        for($i = 0; $i < $size; $i++) {
            $currentIsUnder = $points[$i]->y < $this->y;
            if ($this->isBetween($points[$last], $points[$i])) {
                return true;
            }
            $ax = ($points[$last]->x - $this->x);
            $ay = ($points[$last]->y - $this->y);
            $bx = ($points[$i]->x  - $this->x);
            $by = ($points[$i]->y  - $this->y);
            $t = ($ax*($by - $ay) - $ay*($bx - $ax));
            if($currentIsUnder && !$lastIsUnder) {
                if($t > 0) {
                    $intersectionsCount += 1;
                }
            }

            if(!$currentIsUnder && $lastIsUnder){
                if($t < 0) {
                    $intersectionsCount += 1;
                }
            }

            $last = $i;
            $lastIsUnder = $currentIsUnder;
        }

        return $intersectionsCount % 2 == 1;
    }

    public function inPolygon2(Point $a, $p) {
        $parity = false;
        for ($i = 0;  $i < sizeof($p); $i++) {
            $e = $p->edge();
            switch ($e->edgeType($a)) {
                case TOUCHING:
                    return true;
                case CROSSING:
                    $parity = !$parity;
            }
        }
        return $parity;
    }

    protected function inTriangle(Point $a, Point $b, Point $c) {
        return  ($this->classify($a, $b) != self::LEFT) &&
                ($this->classify($b, $c) != self::LEFT) &&
                ($this->classify($c, $a) != self::LEFT);
    }

}