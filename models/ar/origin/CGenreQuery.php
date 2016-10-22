<?php

namespace app\models\ar\origin;

use app\models\ar;

/**
 * This is the ActiveQuery class for [[CGenre]].
 *
 * @see CGenre
 */
class CGenreQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return CGenre[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CGenre|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
