<?php
/**
 * Created by PhpStorm.
 * User: ZyManch
 * Date: 14.04.2016
 * Time: 10:52
 */

namespace app\models\parser;


class Point {

    const LEFT = 'left';
    const RIGHT = 'right';
    const ONLINE = 'online';

    public $x;
    public $y;

    public $direction;
    public $side = [];

    static $_count = 0;

    public function __construct($x, $y, $direction = null, $side = []) {
        $this->x = $x;
        $this->y = $y;
        $this->direction = $direction;
        if (!is_array($side)) {
            $side = [$side];
        }
        $this->side = $side;
        self::$_count++;
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