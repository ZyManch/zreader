<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use yii\models;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class ParserController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionIndex($dir = '/schooldxd/01/slides/')
    {
        $dir = dirname(__FILE__).'/../'.ltrim($dir,'/');
        $files = scandir($dir);
        $files = array('.','..','p_0012.jpg');
        foreach (array_slice($files, 2) as $file) {
            $fullName = $dir.$file;
            $grid = new models\ImageGrid($fullName);

            $width = $grid->getWidth();
            $height = $grid->getHeight();
            $frames = array();
            for ($y=0;$y<=$height;$y++) {
                for ($x=$width;$x>=0;$x--) {
                    $point = new models\Point($x, $y);
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
    }
}
