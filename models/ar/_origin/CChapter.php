<?php

namespace app\models\ar\_origin;

use Yii;
use app\models\ar;

/**
 * This is the model class for table "chapter".
 *
 * @property string $chapter_id
 * @property string $manga_id
 * @property string $number
 * @property string $title
 * @property string $created
 *
 * @property ar\Manga\Model $manga
 * @property ar\Image\Model[] $ar\Image\Models
 * @property ar\Task\Model[] $ar\Task\Models
 */
class CChapter extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'chapter';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['manga_id', 'number'], 'required'],
            [['manga_id'], 'integer'],
            [['number'], 'number'],
            [['created'], 'safe'],
            [['title'], 'string', 'max' => 250],
            [['manga_id'], 'exist', 'skipOnError' => true, 'targetClass' => ar\Manga\Model::className(), 'targetAttribute' => ['manga_id' => 'manga_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'chapter_id' => 'Chapter ID',
            'manga_id' => 'Manga ID',
            'number' => 'Number',
            'title' => 'Title',
            'created' => 'Created',
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
        public function getManga()
    {
    return $this->hasOne(ar\Manga\Model::className(), ['manga_id' => 'manga_id']);
    }
        /**
     * @return \yii\db\ActiveQuery
     */
        public function getImages()
    {
        return $this->hasMany(ar\Image\Model::className(), ['chapter_id' => 'chapter_id']);
    }
        /**
     * @return \yii\db\ActiveQuery
     */
        public function getTasks()
    {
        return $this->hasMany(ar\Task\Model::className(), ['chapter_id' => 'chapter_id']);
    }
    
    /**
     * @inheritdoc
     * @return \app\models\ar\Chapter\Query the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\ar\Chapter\Query(get_called_class());
    }
}
