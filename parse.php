<?php
/**
 * Created by PhpStorm.
 * User: ZyManch
 * Date: 13.04.2016
 * Time: 14:52
 */
include dirname(__FILE__).'/vendor/autoload.php';
$dir = dirname(__FILE__).'/schooldxd/01/slides/';
$files = scandir($dir);
$files = array('.','..','p_0012.jpg');
foreach (array_slice($files, 2) as $file) {
    $fullName = $dir.$file;
    $grid = new ImageGrid($fullName);

    $width = $grid->getWidth();
    $height = $grid->getHeight();
    $frames = array();
    for ($y=0;$y<=$height;$y++) {
        for ($x=$width;$x>=0;$x--) {
            $point = new Point($x, $y);
            if (!$grid->isWhite($point)) {
                $frame = $grid->extractFrame($point);
                //$frame->optimize();
                //cloneImageAndDeleteFrame($gd, null, $frame, 0);
                $frames[] = $frame;
                break(2);
            }
        }
    }
    $gd = imagecreatefromjpeg($fullName);
    $red = imagecolorallocate($gd, 250,0,0);
    foreach ($frames as $frame) {
        $lastPoint = null;
        foreach ($frame->getPoints() as $point) {
            if ($lastPoint) {
                imageline($gd, $point->x, $point->y, $lastPoint->x,$lastPoint->y, $red);
            }
            $lastPoint = $point;
        }
    }
    imagejpeg($gd,dirname(__FILE__).'/blank.jpg');
    break;

}