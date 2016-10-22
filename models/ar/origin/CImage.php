<?php

namespace app\models\ar\origin;

use Yii;
use app\models\ar;

/**
 * This is the model class for table "image".
 *
 * @property string $page_id
 * @property string $chapter_id
 * @property integer $page
 * @property integer $position
 * @property string $filename
 * @property string $type
 * @property integer $width
 * @property integer $height
 * @property integer $left
 * @property integer $top
 *
 * @property ar\Chapter $chapter
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
            [['chapter_id', 'page', 'position', 'filename', 'width', 'height', 'left', 'top'], 'required'],
            [['chapter_id', 'page', 'position', 'width', 'height', 'left', 'top'], 'integer'],
            [['type'], 'string'],
            [['filename'], 'string', 'max' => 64],
            [['chapter_id', 'page', 'position'], 'unique', 'targetAttribute' => ['chapter_id', 'page', 'position'], 'message' => 'The combination of Chapter ID, Page and Position has already been taken.'],
            [['chapter_id'], 'exist', 'skipOnError' => true, 'targetClass' => ar\Chapter::className(), 'targetAttribute' => ['chapter_id' => 'chapter_id']],
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
        return $this->hasOne(ar\Chapter::className(), ['chapter_id' => 'chapter_id']);
    }

    /**
     * @inheritdoc
     * @return CImageQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CImageQuery(get_called_class());
    }
}
