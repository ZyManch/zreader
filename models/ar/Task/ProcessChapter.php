<?php

namespace app\models\ar\Task;

use app\models\ar;
use app\models\image\Image;
use app\models\image\Jpeg;
use app\models\image\Png;
use app\models\parser\Frame;
use app\models\parser\FrameProvider;
use app\models\parser\Point;
use app\models\parser\Side;


class ProcessChapter extends Model
{

    const PNG_TO_JPG_COEFFICIENT = 1.5;

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
            $this->_processFile($index, $file);
            //$this->_stdoutStat();
        }
    }

    protected function _processFile($index, $file) {
        $this->stdout("Parsing ".$file);
        $fullName = $this->filename.$file;
        if (!in_array(pathinfo($file,PATHINFO_EXTENSION),array('jpg','png'))) {
            return;
        }
        $grid = new FrameProvider($fullName);
        $isReverted = ($this->manga->is_reverted == ar\Manga\Model::IS_REVERTED_YES);
        $frames = $grid->extractFrames($isReverted);
        $this->stdout("Frames found: ".sizeof($frames));
        try {
            $gd = Jpeg::loadFile($fullName);
        } catch (\Exception $e) {
            $gd = Png::loadFile($fullName);
        }
        /** @var Frame $frame */
        foreach ($frames as $position => $frame) {
            $this->_saveFrame($this->chapter, $frame, $gd, $position, $index+1);
        }
        gc_collect_cycles();
    }

    protected function _stdoutStat() {
        $this->stdout(sprintf(
            'Points: %d, Side: %d, Frame: %d, Image: %d',
            Point::$_count,
            Side::$_count,
            Frame::$_count,
            Image::$_count
        ));
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
        $this->stdout("Image saved to db");
        $lastPoint = null;
        /** @var Png $framePng */
        $framePng = Jpeg::create($image->width,$image->height);
        $framePng->fillBg();

        for ($x=$frame->getMinX();$x<=$frame->getMaxX();$x++) {
            for ($y=$frame->getMinY();$y<=$frame->getMaxY();$y++) {
                $point = new Point($x, $y);
                if ($frame->inFrame($point)) {
                    $color = $gd->getRGB($x, $y);
                    $framePng->setPixel(
                        $x-$frame->getMinX(),
                        $y-$frame->getMinY(),
                        $color,
                        true
                    );
                }
            }
        }
        /*
        $pngFileSize = $framePng->getFileSize();
        $frameJpeg = $framePng->toJpeg();
        $jpgFileSize = $frameJpeg->getFileSize();
        $this->stdout(sprintf(
            'Selecting engine [png %dkb vs jpg %dkb]',
            round($pngFileSize/1024),
            round($jpgFileSize/1024)
        ));
        if ($pngFileSize * self::PNG_TO_JPG_COEFFICIENT < $jpgFileSize) {
            $frameGd = $framePng;
        } else {
            $frameGd = $frameJpeg;
        }
        /**/
        $frameGd = $framePng;
        $image->filename = $chapter->number.'_'.$page.'_'.$position.'.'.$frameGd->getExtension();
        if (!$image->save()) {
            throw new \Exception('Error create image:'.json_encode($image->getFirstErrors()));
        }
        $fullPath = $image->getFullPath();
        $frameGd->save($fullPath, 40);
        $this->stdout("Image saved to disk as ".$frameGd->getExtension());
    }

    protected function _getFiles() {
        $files = scandir($this->filename);
        $files = array_slice($files,2);
        sort($files,SORT_NUMERIC);
        return $files;
    }
}
