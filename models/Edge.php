<?php
/**
 * Created by PhpStorm.
 * User: ZyManch
 * Date: 14.04.2016
 * Time: 10:52
 */

namespace app\models;


class Edge {

    const LEFT = 'left';
    const RIGHT = 'right';
    const ONLINE = 'online';

    public $a;
    public $b;

    public function __construct(Point $a,Point  $b) {
        $this->a = $a;
        $this->b = $b;
    }

    public function edgeType(Point $a) {
        Point $v = $this->org();
        Point $w = $this->dest();
        switch ($a->classify($this->a, $this->b)) {
            case LEFT:
                return (($v->y<$a->y)&&($a->y<=$w->y)) ? CROSSING : INESSENTIAL;
            case RIGHT:
                return (($w->y<$a->y)&&($a->y<=$v->y)) ? CROSSING : INESSENTIAL;
            case BETWEEN:
            case ORIGIN:
            case DESTINATION:
                return TOUCHING;
            default:
                return INESSENTIAL;
        }
    }

}