<?php

namespace app\models\ar;

use app\models\ar\origin\CTask;
use app\models\ar\task\ProcessChapter;
use app\models\ar\task\UploadChapter;
use app\models\ar\task\UploadManga;
use app\models\ar\task\UploadMangaList;
use Yii;

/**
 * This is the model class for table "task".
 *
 * @property string $task_id
 * @property string $manga_id
 * @property string $season_id
 * @property string $chapter_id
 * @property string $task
 * @property string $filename
 * @property string $status
 *
 * @property Chapter $chapter
 * @property Manga $manga
 * @property Season $season
 */
abstract class Task extends CTask
{

    const TASK_UPLOAD_MANGA = 'upload_manga';
    const TASK_UPLOAD_CHAPTER = 'upload_chapter';
    const TASK_PROCESS_CHAPTER = 'process_chapter';
    const TASK_UPLOAD_MANGA_LIST = 'upload_list';


    const STATUS_SUCCESS = 'success';
    const STATUS_WAIT = 'wait';
    const STATUS_PROGRESS = 'progress';
    const STATUS_ERROR = 'error';


    public function process() {
        $this->status = self::STATUS_PROGRESS;
        $this->save();
        try {
            $this->_process();
            $this->status = self::STATUS_SUCCESS;
            $this->save();
        } catch (\Exception $e) {
            $this->status = self::STATUS_ERROR;
            $this->save();
            throw $e;
        }
    }

    abstract protected function _process();

    public static function instantiate($row) {
        switch ($row['task']) {
            case self::TASK_UPLOAD_MANGA:
                return new UploadManga();
            case self::TASK_UPLOAD_CHAPTER:
                return new UploadChapter();
            case self::TASK_PROCESS_CHAPTER:
                return new ProcessChapter();
            case self::TASK_UPLOAD_MANGA_LIST:
                return new UploadMangaList();
        }
    }

    protected function _requestPage($filename = null) {
        if (is_null($filename)) {
            $filename = $this->filename;
        }
        if (substr($filename,0,4)=='http') {
            $curl = curl_init();
            if(!$curl) {
                throw new \Exception('CURL not installed');
            }
            $agent= 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';
            curl_setopt($curl, CURLOPT_URL,$filename);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_VERBOSE, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_USERAGENT, $agent);
            curl_setopt($curl, CURLOPT_HEADER, false);
            $html = curl_exec($curl);
            curl_close($curl);
            return $html;
        } else {
            return file_get_contents($filename);
        }
    }
}