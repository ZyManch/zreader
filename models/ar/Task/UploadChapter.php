<?php

namespace app\models\ar\task;

use app\models\ar;
use Yii;
use yii\helpers\Url;


class UploadChapter extends Model
{

    public function init() {
        parent::init();
        $this->task = self::TASK_UPLOAD_CHAPTER;
    }

    protected function _process() {
        $html = $this->_requestPage();
        $images = $this->_getImages($html);
        /** @var ar\Chapter\Model $chapter */
        $chapter = $this->getChapter()->one();
        $storingFolder = Url::to('@app').'/public/manga/page/'.
                           $chapter->manga->url.'/'.
                           $chapter->number.'/';
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
        $this->_createOrUpdateTask(
            $this->manga_id,
            $this->chapter_id,
            self::TASK_PROCESS_CHAPTER,
            $storingFolder
        );
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
