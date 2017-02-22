<?php

namespace app\models\ar\Storage;

use app\models\ar;

class Model extends ar\_origin\CStorage {

    public static function instantiate($row) {
        switch ($row['storage_engine_id']) {
            case ar\StorageEngine\Model::ENGINE_EXTERNAL_ID:
                return new ExternalStorage();
            case ar\StorageEngine\Model::ENGINE_INTERNAL_ID:
                return new InternalStorage();
            case ar\StorageEngine\Model::ENGINE_PARSED_ID:
                return new ParsedStorage();
            default:
                throw new \Exception('Unknown engine storage: '.$row['storage_engine_id']);
        }
    }


    public function getViewPath(ar\Image\Model $image) {
        return $this->url.$this->_getRelativePath($image);
    }

    public function getFullPath(ar\Image\Model $image) {
        return $this->path.$this->_getRelativePath($image);
    }

     function _getRelativePath(ar\Image\Model $image) {
        throw new \Exception('Used abstract class '.__CLASS__);
     }

}