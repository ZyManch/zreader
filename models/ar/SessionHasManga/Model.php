<?php

namespace app\models\ar\SessionHasManga;

use app\models\ar;

class Model extends ar\_origin\CSessionHasManga {

    const STATUS_HIDE = 'hide';
    const STATUS_FAVORITE = 'favorite';
    const STATUS_STARTED = 'started';
    const STATUS_UNKNOWN = 'unknown';


    public function behaviors()
    {
        return [
            \app\behaviors\SessionHasManga::className(),
        ];
    }
}