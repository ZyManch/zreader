<?php

namespace app\models\ar\task;

use app\models\ar\Chapter;
use app\models\ar\Task;
use Yii;
use yii\helpers\Url;


class UploadChapter extends Task
{

    public function init() {
        parent::init();
        $this->task = self::TASK_UPLOAD_CHAPTER;
    }

    protected function _process() {
        $html = $this->_requestPage();
        $images = $this->_getImages($html);
        /** @var Chapter $chapter */
        $chapter = $this->getChapter()->one();
        $storingFolder = Url::to('@app').'/public/manga/page/'.
                           $chapter->season->manga->manga_id.'/'.
                           $chapter->season->season_id.'/'.
                           $chapter->chapter_id.'/';
        if (!file_exists($storingFolder)) {
            mkdir($storingFolder,0777,true);
        }
        foreach ($images as $index => $imagePath) {
            $file = $this->_requestPage($imagePath);
            file_put_contents(
                $storingFolder.($index+1).'.jpg',
                $file
            );
        }
        $task = new ProcessChapter();
        $task->manga_id = $this->manga_id;
        $task->season_id = $this->season_id;
        $task->chapter_id = $this->chapter_id;
        $task->filename = $storingFolder;
        if (!$task->save()) {
            throw new \Exception('Error create task: '.implode(',',$task->getFirstErrors()));
        }
    }


    protected function _getImages($html) {
        if (!preg_match('#rm_h\.init\( ([^)]+), [0-9]+, (true|false)\)#',$html, $matches)) {
            return [];
        }
        $chapters = json_decode(str_replace("'",'"', $matches[1]),1);
        $result = [];
        foreach ($chapters as $chapter) {
            $result[] = $chapter[1].$chapter[0].$chapter[2];
        }
        return $result;
    }
}
