<?php

namespace app\models\ar\origin;

use app\models\ar;

/**
 * This is the ActiveQuery class for [[CImage]].
 *
 * @see CImage
 */
class CImageQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return CImage[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CImage|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
