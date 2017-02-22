<?php

namespace app\models\ar\Storage;

use app\models\ar;

class ParsedStorage extends Model {


    public function _getRelativePath(ar\Image\Model $image) {
        return $image->chapter->manga->url.'/'.$image->chapter->number.'/'.$image->filename;
    }

}