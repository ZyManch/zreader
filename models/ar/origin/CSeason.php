<?php

namespace app\models\ar\origin;

use Yii;
use app\models\ar;

/**
 * This is the model class for table "season".
 *
 * @property string $season_id
 * @property string $manga_id
 * @property string $title
 * @property integer $position
 *
 * @property ar\Chapter[] $ar\Chapters
 * @property ar\Manga $manga
 * @property ar\Task[] $ar\Tasks
 */
class CSeason extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'season';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['manga_id', 'title', 'position'], 'required'],
            [['manga_id', 'position'], 'integer'],
            [['title'], 'string', 'max' => 128],
            [['manga_id'], 'exist', 'skipOnError' => true, 'targetClass' => ar\Manga::className(), 'targetAttribute' => ['manga_id' => 'manga_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'season_id' => 'Season ID',
            'manga_id' => 'Manga ID',
            'title' => 'Title',
            'position' => 'Position',
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChapters()
    {
        return $this->hasMany(ar\Chapter::className(), ['season_id' => 'season_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManga()
    {
        return $this->hasOne(ar\Manga::className(), ['manga_id' => 'manga_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(ar\Task::className(), ['season_id' => 'season_id']);
    }

    /**
     * @inheritdoc
     * @return CSeasonQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CSeasonQuery(get_called_class());
    }
}
