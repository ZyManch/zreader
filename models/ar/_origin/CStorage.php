<?php

namespace app\models\ar\_origin;

use Yii;
use app\models\ar;

/**
 * This is the model class for table "storage".
 *
 * @property string $storage_id
 * @property string $path
 * @property string $url
 * @property string $storage_engine_id
 *
 * @property ar\Image\Model[] $ar\Image\Models
 * @property ar\StorageEngine\Model $storageEngine
 * @property ar\Task\Model[] $ar\Task\Models
 */
class CStorage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'storage';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['path', 'url', 'storage_engine_id'], 'required'],
            [['storage_engine_id'], 'integer'],
            [['path'], 'string', 'max' => 256],
            [['url'], 'string', 'max' => 128],
            [['storage_engine_id'], 'exist', 'skipOnError' => true, 'targetClass' => ar\StorageEngine\Model::className(), 'targetAttribute' => ['storage_engine_id' => 'storage_engine_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'storage_id' => 'Storage ID',
            'path' => 'Path',
            'url' => 'Url',
            'storage_engine_id' => 'Storage Engine ID',
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
        public function getImages()
    {
        return $this->hasMany(ar\Image\Model::className(), ['storage_id' => 'storage_id']);
    }
        /**
     * @return \yii\db\ActiveQuery
     */
        public function getStorageEngine()
    {
    return $this->hasOne(ar\StorageEngine\Model::className(), ['storage_engine_id' => 'storage_engine_id']);
    }
        /**
     * @return \yii\db\ActiveQuery
     */
        public function getTasks()
    {
        return $this->hasMany(ar\Task\Model::className(), ['storage_id' => 'storage_id']);
    }
    
    /**
     * @inheritdoc
     * @return \app\models\ar\Storage\Query the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\ar\Storage\Query(get_called_class());
    }
}
