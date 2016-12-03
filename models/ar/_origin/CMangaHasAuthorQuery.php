<?php

namespace app\models\ar\_origin;

use app\models\ar;

/**
 * This is the ActiveQuery class for [[CMangaHasAuthor]].
 *
 * @see CMangaHasAuthor
 */
class CMangaHasAuthorQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return CMangaHasAuthor[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CMangaHasAuthor|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
