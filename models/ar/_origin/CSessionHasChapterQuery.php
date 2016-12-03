<?php

namespace app\models\ar\_origin;

use app\models\ar;

/**
 * This is the ActiveQuery class for [[CSessionHasChapter]].
 *
 * @see CSessionHasChapter
 */
class CSessionHasChapterQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return CSessionHasChapter[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CSessionHasChapter|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
