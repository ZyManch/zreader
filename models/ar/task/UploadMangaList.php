<?php

namespace app\models\ar\task;

use app\models\ar\Task;
use Yii;


class UploadMangaList extends Task
{

    const MANGA_PER_PAGE = 70;

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
                $this->_createOrUpdateTask(null,null,null,self::TASK_UPLOAD_MANGA, $url);
            }
            $offset+=self::MANGA_PER_PAGE;
        } while (sizeof($newManga) > 0);
    }


    protected function _collectManga($offset, $page) {
        $domain = rtrim($this->filename,'/');
        $url = sprintf(
            $domain.'/list?type=&sortType=votes&offset=%d&max=%d',
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
            $result[] = $domain.$url;
        }
        return $result;
    }



}
