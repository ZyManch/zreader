<?php

namespace app\models\ar\SessionHasManga;

use app\models\ar;

class Model extends ar\_origin\CSessionHasManga {

    const STATUS_HIDE = 'hide';
    const STATUS_FAVORITE = 'favorite';
    const STATUS_STARTED = 'started';
    const STATUS_DEFERRED = 'deferred';
    const STATUS_UNKNOWN = 'unknown';

    const READ_FINISHED_YES = 'yes';
    const READ_FINISHED_NO = 'no';

    protected static $_cache = [];

    public function behaviors()
    {
        return [
            \app\behaviors\SessionHasManga::className(),
        ];
    }

    public static function instantiate($row) {
        if (isset($row['manga_id'])) {
            $mangaId = $row['manga_id'];
            if (!isset(self::$_cache[$mangaId])) {
                self::$_cache[$mangaId] = new Model();
            }
            $model = self::$_cache[$mangaId];
        } else {
            $model = new Model();;
        }
        return $model;
    }
}