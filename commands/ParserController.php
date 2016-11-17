<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use app\models;
use app\models\ar;
use yii\helpers\Url;
use yii\web\UrlManager;

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

    public $chapter_id;

    public function options()
    {
        return ['chapter_id'];
    }

    public function optionAliases()
    {
        return ['c' => 'chapter_id'];
    }

    public function actionIndex($dir = '/schooldxd/01/slides/01/')
    {
        ini_set('memory_limit','512M');
        $dir = dirname(__FILE__).'/../'.ltrim($dir,'/');
        $files = scandir($dir);
        $chapter = $this->_getChapter($this->chapter_id);
        $page = 1;
        foreach (array_slice($files, 2) as $file) {
            if (!in_array(pathinfo($file,PATHINFO_EXTENSION),array('jpg','png'))) {
                continue;
            }
            $fullName = $dir.$file;
            $frames = $this->_extractFrames($fullName);
            $this->stdout("Writing result\n");
            $gd = imagecreatefromjpeg($fullName);
            /** @var models\Frame $frame */
            foreach ($frames as $position => $frame) {
                $this->stdout("Save frame ".$position."\n");
                $this->_saveFrame($chapter, $frame, $gd, $position, $page);
            }
            imagedestroy($gd);
            $page++;
        }
    }

    protected function _saveFrame(ar\Chapter $chapter, models\Frame $frame, $gd, $position, $page) {
        $image = new ar\Image();
        $image->position = $position;
        $image->page = $page;
        $image->chapter_id = $chapter->chapter_id;
        $image->left = $frame->getMinX();
        $image->top = $frame->getMinY();
        $image->width = $frame->getMaxX()-$frame->getMinX();
        $image->height = $frame->getMaxY()-$frame->getMinY();
        $image->filename = $chapter->chapter_id.'_'.$page.'_'.$position.'.jpg';
        if (!$image->save()) {
            throw new \Exception('Error create image:'.json_encode($image->getFirstErrors()));
        }
        $lastPoint = null;
        $frameGd = imagecreatetruecolor($image->width,$image->height);
        imagealphablending($frameGd, false);
        $transparency = imagecolorallocatealpha($frameGd, 255, 255, 255, 127);
        imagecolortransparent($frameGd, $transparency);
        imagefill($frameGd, 0, 0, $transparency);
        imagesavealpha($frameGd, true);
        $colors = array();
        $width = imagesx($gd);
        $height = imagesy($gd);
        for ($x=$frame->getMinX();$x<=$frame->getMaxX();$x++) {
            for ($y=$frame->getMinY();$y<=$frame->getMaxY();$y++) {
                $point = new models\Point($x, $y);
                if ($frame->inFrame($point)) {
                    if ($x<0 || $y<0 || $x>$width || $y > $height) {
                        $color = 0xffffff;
                    } else {
                        $color = imagecolorat($gd,$x,$y);
                    }

                    if (!isset($colors[$color])) {
                        $r = ($color >> 16) & 0xFF;
                        $g = ($color >> 8) & 0xFF;
                        $b = $color & 0xFF;
                        $colors[$color] = imagecolorallocate($frameGd,$r, $g, $b);
                    }
                    imagesetpixel($frameGd,$x-$frame->getMinX(),$y-$frame->getMinY(),$colors[$color]);
                }
            }
        }
        $this->stdout("Saved with pallet: ".sizeof($colors)."\n");
        imagejpeg($frameGd,$image->getFullPath(),80);
        imagedestroy($frameGd);

    }

    protected function _extractFrames($fullName) {

        $grid = new models\ImageGrid($fullName);

        $width = $grid->getWidth();
        $height = $grid->getHeight();
        $dumpFileName = $fullName.'.php';
        if (file_exists($dumpFileName) && false) {
            $frames = unserialize(file_get_contents($dumpFileName));
        } else {
            $frames = array();
            for ($y = 0; $y <= $height; $y++) {
                for ($x = $width; $x >= 0; $x--) {
                    $point = new models\Point($x, $y);
                    if (!$grid->isWhite($point)) {
                        $point = new models\Point($x+1, $y, models\ImageGrid::BOTTOM);
                        $frame = $grid->extractFrame($point);
                        $this->stdout('Found frame ' . $frame->getTitle() . '['.sizeof($frame->getPoints()).']. ');
                        $this->stdout('Cuting: ');
                        $grid->markAsWhite($frame);
                        $this->stdout("done. Adding to list: ");
                        if ($frame->isSmall()) {
                            $this->stdout("skipped.\n");
                        } else {
                            $frames[] = $frame;
                            $this->stdout("done.\n");
                        }
                    }
                }
            }
            file_put_contents($dumpFileName, serialize($frames));
        }
        return $frames;
    }

    /**
     * @param $chapterId
     * @return ar\Chapter
     * @throws \Exception
     */
    protected function _getChapter($chapterId) {
        /** @var ar\Chapter $chapter */
        $chapter = ar\Chapter::findOne($chapterId);
        if (!$chapter) {
            throw new \Exception('Chapter not found');
        }
        foreach ($chapter->getImages()->all() as $image) {
            $image->delete();
        }
        return $chapter;
    }
}
