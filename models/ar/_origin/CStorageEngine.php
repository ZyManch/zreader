<?php

namespace app\models\ar\_origin;

use Yii;
use app\models\ar;

/**
 * This is the model class for table "storage_engine".
 *
 * @property string $storage_engine_id
 * @property string $name
 * @property integer $priority
 *
 * @property ar\Storage\Model[] $ar\Storage\Models
 */
class CStorageEngine extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'storage_engine';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['priority'], 'integer'],
            [['name'], 'string', 'max' => 256],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'storage_engine_id' => 'Storage Engine ID',
            'name' => 'Name',
            'priority' => 'Priority',
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
        public function getStorages()
    {
        return $this->hasMany(ar\Storage\Model::className(), ['storage_engine_id' => 'storage_engine_id']);
    }
    
    /**
     * @inheritdoc
     * @return \app\models\ar\StorageEngine\Query the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\ar\StorageEngine\Query(get_called_class());
    }
}
