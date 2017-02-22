<?php

namespace app\models\ar\StorageEngine;

use app\models\ar;

class Model extends ar\_origin\CStorageEngine {

    const ENGINE_EXTERNAL_ID = 1;
    const ENGINE_INTERNAL_ID = 2;
    const ENGINE_PARSED_ID = 3;
}