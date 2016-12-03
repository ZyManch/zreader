<?php

namespace app\models\ar\Author;

use app\models\ar;
/**
 * This is the model class for table "author".
 *
 * @property string $author_id
 * @property string $name
 * @property string $avatar
 *
 * @property ar\Manga\Model[] $ar\Mangas
 */
class Model extends ar\_origin\CAuthor
{

}
