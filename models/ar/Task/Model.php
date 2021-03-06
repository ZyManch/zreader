<?php

namespace app\models\ar\Task;

use app\models\ar;
use Yii;
use yii\console\Controller;

/**
 * This is the model class for table "task".
 *
 * @property string $task_id
 * @property string $manga_id
 * @property string $chapter_id
 * @property string $task
 * @property string $filename
 * @property string $status
 *
 * @property ar\Chapter\Model $chapter
 * @property ar\Manga\Model $manga
 */
abstract class Model extends ar\_origin\CTask
{

    const TASK_UPLOAD_MANGA = 'upload_manga';
    const TASK_UPLOAD_CHAPTER = 'upload_chapter';
    const TASK_FILL_CHAPTER = 'fill_chapter';
    const TASK_PROCESS_CHAPTER = 'process_chapter';
    const TASK_UPLOAD_MANGA_LIST = 'upload_list';


    const STATUS_SUCCESS = 'success';
    const STATUS_WAIT = 'wait';
    const STATUS_PROGRESS = 'progress';
    const STATUS_ERROR = 'error';

    /** @var  Controller */
    protected $controller;

    protected $_lastTime;

    public function process(Controller $controller = null) {
        $this->controller = $controller;
        $this->_lastTime = microtime(true);
        $this->status = self::STATUS_PROGRESS;
        $this->save();
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $this->_process();
            $this->status = self::STATUS_SUCCESS;
            $this->save();
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            $this->status = self::STATUS_ERROR;
            $this->save();
            throw $e;
        }
    }

    protected function stdout($text, $extend = true) {
        if ($this->controller) {
            $time = microtime(true);
            $duration = $time - $this->_lastTime;
            $this->_lastTime = $time;
            if ($extend) {

                $text = ' > '.$text;
                if (strlen($text)<60) {
                    $text .= str_repeat(' ', 60-strlen($text));
                }
                $text.= ' ['.implode(', ',[
                    number_format(memory_get_usage(true)/1024/1024,1).' mb',
                    number_format($duration,1).' sec',
                ])."]\n";
            }
            $this->controller->stdout($text);
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
        if (!$this->storage->path) {
            $curl = curl_init();
            if(!$curl) {
                throw new \Exception('CURL not installed');
            }
            $agent= 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';
            if (substr(strtolower($filename),0,4)!='http') {
                $filename = $this->storage->url.$filename;
            }
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
            return file_get_contents($this->storage->path.$filename);
        }
    }

    protected function _createOrUpdateTask($mangaId, $chapterId, $taskType,
                                           $storageId, $fileName, $isUniqueFileName = false) {
        $query = self::find()->
            where('task=:task',array(':task'=>$taskType));
        if ($chapterId) {
            $query->andWhere('chapter_id=:chapter',array(':chapter'=>$chapterId));
        } else {
            $query->andWhere('chapter_id is null');
        }
        if ($mangaId) {
            $query->andWhere('manga_id=:manga',array(':manga'=>$mangaId));
        } else {
            $query->andWhere('manga_id is null');
        }
        if ($isUniqueFileName) {
            $query->andWhere('filename=:filename',array(':filename'=>$fileName));
        }
        $task = $query->one();
        if (!$task) {
            $task = self::instantiate(['task'=>$taskType]);
            $task->task = $taskType;
            if ($mangaId) {
                $task->manga_id = $mangaId;
            }
            if ($chapterId) {
                $task->chapter_id = $chapterId;
            }
        }
        $task->storage_id = $storageId;
        $task->filename = $fileName;
        $task->status = self::STATUS_WAIT;
        if (!$task->save()) {
            throw new \Exception('Error create task: '.implode(',',$task->getFirstErrors()));
        }
    }
}
