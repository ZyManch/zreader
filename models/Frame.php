<?php
/**
 * Created by PhpStorm.
 * User: ZyManch
 * Date: 14.04.2016
 * Time: 10:56
 */
namespace yii\models;

class Frame {

    /** @var Point[] */
    protected $_points = array();

    public function addPoint(Point $point) {
        $this->_points[] = $point;
    }

    public function getPoints() {
        return $this->_points;
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
    }


}