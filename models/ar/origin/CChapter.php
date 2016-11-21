<?php

namespace app\models\ar\origin;

use Yii;
use app\models\ar;

/**
 * This is the model class for table "chapter".
 *
 * @property string $chapter_id
 * @property string $season_id
 * @property string $number
 * @property string $title
 * @property string $created
 *
 * @property ar\Season $season
 * @property ar\Image[] $ar\Images
 * @property ar\Task[] $ar\Tasks
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
            [['season_id', 'number'], 'required'],
            [['season_id'], 'integer'],
            [['number'], 'number'],
            [['created'], 'safe'],
            [['title'], 'string', 'max' => 250],
            [['season_id'], 'exist', 'skipOnError' => true, 'targetClass' => ar\Season::className(), 'targetAttribute' => ['season_id' => 'season_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'chapter_id' => 'Chapter ID',
            'season_id' => 'Season ID',
            'number' => 'Number',
            'title' => 'Title',
            'created' => 'Created',
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSeason()
    {
        return $this->hasOne(ar\Season::className(), ['season_id' => 'season_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImages()
    {
        return $this->hasMany(ar\Image::className(), ['chapter_id' => 'chapter_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(ar\Task::className(), ['chapter_id' => 'chapter_id']);
    }

    /**
     * @inheritdoc
     * @return CChapterQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CChapterQuery(get_called_class());
    }
}
