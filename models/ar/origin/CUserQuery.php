<?php

namespace app\models\ar\origin;

use app\models\ar;

/**
 * This is the ActiveQuery class for [[CUser]].
 *
 * @see CUser
 */
class CUserQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return CUser[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CUser|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
