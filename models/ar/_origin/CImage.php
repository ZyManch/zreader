<?php

namespace app\models\ar\_origin;

use Yii;
use app\models\ar;

/**
 * This is the model class for table "image".
 *
 * @property string $page_id
 * @property string $chapter_id
 * @property integer $page
 * @property integer $position
 * @property string $storage_id
 * @property string $filename
 * @property string $type
 * @property integer $width
 * @property integer $height
 * @property integer $left
 * @property integer $top
 *
 * @property ar\Chapter\Model $chapter
 * @property ar\Storage\Model $storage
 */
class CImage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'image';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['chapter_id', 'page', 'position', 'storage_id', 'filename', 'width', 'height', 'left', 'top'], 'required'],
            [['chapter_id', 'page', 'position', 'storage_id', 'width', 'height', 'left', 'top'], 'integer'],
            [['type'], 'string'],
            [['filename'], 'string', 'max' => 256],
            [['chapter_id'], 'exist', 'skipOnError' => true, 'targetClass' => ar\Chapter\Model::className(), 'targetAttribute' => ['chapter_id' => 'chapter_id']],
            [['storage_id'], 'exist', 'skipOnError' => true, 'targetClass' => ar\Storage\Model::className(), 'targetAttribute' => ['storage_id' => 'storage_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'page_id' => 'Page ID',
            'chapter_id' => 'Chapter ID',
            'page' => 'Page',
            'position' => 'Position',
            'storage_id' => 'Storage ID',
            'filename' => 'Filename',
            'type' => 'Type',
            'width' => 'Width',
            'height' => 'Height',
            'left' => 'Left',
            'top' => 'Top',
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
        public function getChapter()
    {
    return $this->hasOne(ar\Chapter\Model::className(), ['chapter_id' => 'chapter_id']);
    }
        /**
     * @return \yii\db\ActiveQuery
     */
        public function getStorage()
    {
    return $this->hasOne(ar\Storage\Model::className(), ['storage_id' => 'storage_id']);
    }
    
    /**
     * @inheritdoc
     * @return \app\models\ar\Image\Query the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\ar\Image\Query(get_called_class());
    }
}
