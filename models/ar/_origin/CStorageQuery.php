<?php

namespace app\models\ar\_origin;

use app\models\ar;

/**
 * This is the ActiveQuery class for [[CStorage]].
 *
 * @see CStorage
 */
class CStorageQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return CStorage[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CStorage|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
