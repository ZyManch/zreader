<?php

namespace app\models\ar\task;

use app\models\ar;
use Yii;
use yii\helpers\Url;


class UploadChapter extends Model
{

    protected $_storages = [];

    public function init() {
        parent::init();
        $this->task = self::TASK_UPLOAD_CHAPTER;
    }

    protected function _process() {
        $this->stdout('Loading image list');
        $html = $this->_requestPage();
        $images = $this->_getImages($html);
        $this->stdout('Prepare upload');
        /** @var ar\Chapter\Model $chapter */
        $chapter = $this->getChapter()->one();
        /** @var ar\Storage\Model $storage */
        $storage = ar\Storage\Model::find()->
            where(['storage_engine_id' => ar\StorageEngine\Model::ENGINE_INTERNAL_ID])->
            one();
        $storingFolder = rtrim($storage->path,'/').'/'.
                           $chapter->manga->url.'/'.
                           $chapter->number.'/';
        if (!file_exists($storingFolder)) {
            mkdir($storingFolder,0777,true);
        }
        foreach ($images as $index => $imagePath) {
            $this->stdout('Downloading '.$imagePath);
            $extension = strtolower(pathinfo($imagePath,PATHINFO_EXTENSION));
            if (!$extension || !in_array($extension, ['png','gif'])) {
                $extension = 'jpg';
            }
            $newFileName = $storingFolder.($index+1).'.'.$extension;
            if (!file_exists($newFileName)) {
                $file = $this->_requestPage($imagePath);
                file_put_contents(
                    $newFileName,
                    $file
                );
            }
            $this->_createOrUpdateInternalImage($storage, $index+1, $newFileName);
            $this->_createOrUpdateExternalImage($imagePath, $index+1, $newFileName);
        }
        $this->_createOrUpdateTask(
            $this->manga_id,
            $this->chapter_id,
            self::TASK_PROCESS_CHAPTER,
            $storage->storage_id,
            $storingFolder
        );
    }

    protected function _createOrUpdateInternalImage(ar\Storage\Model $storage, $page, $localFilePath) {

        $image = ar\Image\Model::find()->
            where(['position' => 0,'page'=>$page,'chapter_id'=>$this->chapter_id,'storage_id'=>$storage->storage_id])->
            one();
        if (!$image) {
            $image = new ar\Image\Model();
            $image->position = 0;
            $image->page = $page;
            $image->chapter_id = $this->chapter_id;
            $image->storage_id = $storage->storage_id;
        }
        list($width, $height) = getimagesize($localFilePath);
        if (!$width) {
            $width = 0;
        }
        if (!$height) {
            $height = 0;
        }
        $image->left = 0;
        $image->top = 0;
        $image->width = $width;
        $image->height = $height;
        $image->filename = basename($localFilePath);
        if (!$image->save()) {
            throw new \Exception('Can`t save image: '.implode(',',$image->getFirstErrors()));
        }
    }

    protected function _createOrUpdateExternalImage($url, $page, $localFilePath) {
        $parts = explode('/',$url,5);
        $partsToMerge = 3;
        if ($parts[3] == 'auto') {
            $partsToMerge = 4;
        }
        $storageUrl = implode('/',array_splice($parts,0, $partsToMerge));
        $storage = $this->_getStorageByUrl($storageUrl);

        $fileName = implode('/', $parts);
        list($width, $height) = getimagesize($localFilePath);
        if (!$width) {
            $width = 0;
        }
        if (!$height) {
            $height = 0;
        }
        $image = ar\Image\Model::find()->
            where(['position' => 0,'page'=>$page,'chapter_id'=>$this->chapter_id,'storage_id'=>$storage->storage_id])->
            one();
        if (!$image) {
            $image = new ar\Image\Model();
            $image->position = 0;
            $image->page = $page;
            $image->chapter_id = $this->chapter_id;
            $image->storage_id = $storage->storage_id;
        }
        $image->left = 0;
        $image->top = 0;
        $image->width = $width;
        $image->height = $height;
        $image->filename = $fileName;
        if (!$image->save()) {
            throw new \Exception('Can`t save image: '.implode(',',$image->getFirstErrors()));
        }
    }

    protected function _getStorageByUrl($storageUrl) {
        $storageUrl = rtrim($storageUrl,'/').'/';
        $storage = (isset($this->_storages[$storageUrl]) ? $this->_storages[$storageUrl] : null);
        if (!$storage) {
            $storage = ar\Storage\Model::find()->
            where('url like :url',[':url' => $storageUrl])->
            one();
        }
        if (!$storage) {
            $storage = new ar\Storage\ExternalStorage();
            $storage->storage_engine_id = ar\StorageEngine\Model::ENGINE_EXTERNAL_ID;
            $storage->url = $storageUrl;
            $storage->path = '/';
            if (!$storage->save()) {
                throw new \Exception('Can`t save storage: '.implode(',',$storage->getFirstErrors()));
            }
        }
        $this->_storages[$storageUrl] = $storage;
        return $storage;
    }

    protected function _getImages($html) {
        if (!preg_match('#rm_h\.init\( ([^)]+), [0-9]+, (true|false)\)#',$html, $matches)) {
            return [];
        }
        $chapters = json_decode(str_replace("'",'"', $matches[1]),1);
        $result = [];
        foreach ($chapters as $chapter) {
            $fileName = $chapter[1].$chapter[0].$chapter[2];
            if (substr(strtolower($fileName),0,4)!='http') {
                $fileName = $this->storage->url.ltrim($fileName,'/');
            }
            $result[] = $fileName;
        }
        return $result;
    }
}
