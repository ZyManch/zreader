<?php

namespace app\models\ar\Image;

use \app\models\ar;

class Model extends ar\_origin\CImage {

    public function getFullPath() {
        return $this->storage->getFullPath($this);
    }

    public function getViewPath() {
        return $this->storage->getViewPath($this);
    }

    public function getRelativePath() {
        return $this->chapter->manga->url.'/'.$this->chapter->number.'/'.$this->filename;
    }

}
