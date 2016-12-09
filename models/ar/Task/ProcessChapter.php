<?php

namespace app\models\ar\Task;

use app\models\ar;
use app\models\image\Image;
use app\models\image\Jpeg;
use app\models\image\Png;
use app\models\parser\Frame;
use app\models\parser\ImageGrid;
use app\models\parser\Point;


class ProcessChapter extends Model
{

    public function init() {
        parent::init();
        $this->task = self::TASK_PROCESS_CHAPTER;
    }

    protected function _process() {
        $files = $this->_getFiles();
        foreach ($this->chapter->getImages()->all() as $image) {
            $image->delete();
        }
        $this->stdout("Old files deleted");
        foreach ($files as $index => $file) {
            if ($file != '4.jpg') {
                continue;
            }
            $this->stdout("Parsing ".$file);
            $fullName = $this->filename.$file;
            if (!in_array(pathinfo($file,PATHINFO_EXTENSION),array('jpg','png'))) {
                continue;
            }
            $frames = $this->_extractFrames($fullName);
            $this->stdout("All frames found");
            try {
                $gd = Jpeg::loadFile($fullName);
            } catch (\Exception $e) {
                $gd = Png::loadFile($fullName);
            }
            /** @var Frame $frame */
            foreach ($frames as $position => $frame) {
                $this->_saveFrame($this->chapter, $frame, $gd, $position, $index+1);
            }

        }
        throw new \Exception('skipped');
    }

    protected function _saveFrame(ar\Chapter\Model $chapter, Frame $frame, Image $gd, $position, $page) {
        $image = new ar\Image\Model();
        $image->position = $position;
        $image->page = $page;
        $image->chapter_id = $chapter->chapter_id;
        $image->left = $frame->getMinX();
        $image->top = $frame->getMinY();
        $image->width = $frame->getMaxX()-$frame->getMinX();
        $image->height = $frame->getMaxY()-$frame->getMinY();
        $image->filename = $chapter->number.'_'.$page.'_'.$position.'.jpg';
        if (!$image->save()) {
            throw new \Exception('Error create image:'.json_encode($image->getFirstErrors()));
        }
        $this->stdout("Image saved to db");
        $lastPoint = null;
        $frameGd = Png::create($image->width,$image->height);
        $frameGd->fillBg();

        for ($x=$frame->getMinX();$x<=$frame->getMaxX();$x++) {
            for ($y=$frame->getMinY();$y<=$frame->getMaxY();$y++) {
                $point = new Point($x, $y);
                if ($frame->inFrame($point)) {
                    $color = $gd->getRGB($x, $y);
                    $frameGd->setPixel(
                        $x-$frame->getMinX(),
                        $y-$frame->getMinY(),
                        $color
                    );
                }
            }
        }
        /*
        $directions = [
            ImageGrid::RIGHT  => ['r'=>0,  'g'=>255,'b'=>0], // green
            ImageGrid::LEFT   => ['r'=>0,  'g'=>0,  'b'=>255], // blue
            ImageGrid::TOP    => ['r'=>255,'g'=>0,  'b'=>0], // red
            ImageGrid::BOTTOM => ['r'=>255,'g'=>255,'b'=>0] // yellow
        ];
        foreach ($frame->getPoints() as $x => $ys) {
            foreach ($ys as $y => $border) {
                if (in_array(ImageGrid::BOTTOM, $border->side)) {
                    $frameGd->setPixel(
                        $x - $frame->getMinX(),
                        $y - $frame->getMinY(),
                        $directions[ImageGrid::BOTTOM]
                    );
                } else {
                    $frameGd->setPixel(
                        $x - $frame->getMinX(),
                        $y - $frame->getMinY(),
                        $directions[$border->side[0]]
                    );
                }
            }
        }*/
        $fullPath = $image->getFullPath();
        $frameGd->save($fullPath, 80);
        $this->stdout("Image saved to disk");
    }

    protected function _extractFrames($fullName) {
        $grid = new ImageGrid($fullName);
        $width = $grid->getWidth();
        $height = $grid->getHeight();
        $frames = [];
        $isReverted = ($this->manga->is_reverted == ar\Manga\Model::IS_REVERTED_YES);
        $yValues = range(0, $height);
        $xValues = range(0, $width);
        if (!$isReverted) {
            rsort($xValues);
        }
        foreach ($yValues as $y) {
            foreach ($xValues  as $x) {
                $frame = $this->_getFrameByXY($grid, $x, $y);
                if ($frame) {
                    $frames[] = $frame;
                }
            }
        }
        return $frames;
    }

    protected function _getFrameByXY(ImageGrid $grid, $x, $y) {
        $point = new Point($x, $y);
        if ($grid->isBackground($point)) {
            return null;
        }
        $isReverted = ($this->manga->is_reverted == ar\Manga\Model::IS_REVERTED_YES);
        $point = new Point($x+($isReverted?-1:1), $y, ImageGrid::BOTTOM);
        $frame = $grid->extractFrame($point);
        $grid->markAsWhite($frame);
        if ($frame->isSmall()) {
            return null;
        }
        $this->stdout("Found frame");
        return $frame;
    }

    protected function _getFiles() {
        $files = scandir($this->filename);
        $files = array_slice($files,2);
        sort($files,SORT_NUMERIC);
        return $files;
    }
}
