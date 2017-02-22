<?php

namespace app\models\ar\task;

use app\models\ar;
use Yii;


class UploadMangaList extends Model
{

    const MANGA_PER_PAGE = 70;

    const STORAGE_ID = 2;

    public function init() {
        parent::init();
        $this->task = self::TASK_UPLOAD_MANGA;
    }

    protected function _process() {
        $offset = 0;
        $page = self::MANGA_PER_PAGE;
        do {
            $newManga = $this->_collectManga($offset, $page);
            foreach ($newManga as $url) {
                $this->_createOrUpdateTask(
                    null,
                    null,
                    self::TASK_UPLOAD_MANGA,
                    self::STORAGE_ID,
                    $url,
                    true
                );
            }
            $offset+=self::MANGA_PER_PAGE;
            $this->stdout("Found $offset mangas");
        } while (sizeof($newManga) > 0);
    }


    protected function _collectManga($offset, $page) {
        $url = sprintf(
            'list?type=&sortType=votes&offset=%d&max=%d',
            $offset,
            $page
        );
        $html = $this->_requestPage($url);
        $mangas = explode('<div class="tile',$html);
        array_shift($mangas);
        $result = [];
        foreach ($mangas as $manga) {
            $manga = explode('<a href="', $manga, 2);
            $manga = explode('"', $manga[1],2);
            $url =  $manga[0];
            if (!$url || substr($url,0,1)!='/' || strlen($url) > 1000 || substr($url,0,13)=='/list/person/') {
                continue;
            }
            $result[] = ltrim($url,'/');
        }
        return $result;
    }



}
