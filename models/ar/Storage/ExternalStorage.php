<?php

namespace app\models\ar\Storage;

use app\models\ar;

class ExternalStorage extends Model {


    public function _getRelativePath(ar\Image\Model $image) {
        return $image->filename;
    }

}