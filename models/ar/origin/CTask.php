<?php

namespace app\models\ar\origin;

use Yii;
use app\models\ar;

/**
 * This is the model class for table "task".
 *
 * @property string $task_id
 * @property string $manga_id
 * @property string $season_id
 * @property string $chapter_id
 * @property string $task
 * @property string $filename
 * @property string $status
 *
 * @property ar\Chapter $chapter
 * @property ar\Manga $manga
 * @property ar\Season $season
 */
class CTask extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'task';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['manga_id', 'season_id', 'chapter_id'], 'integer'],
            [['task', 'filename'], 'required'],
            [['status'], 'string'],
            [['task'], 'string', 'max' => 16],
            [['filename'], 'string', 'max' => 256],
            [['chapter_id'], 'exist', 'skipOnError' => true, 'targetClass' => ar\Chapter::className(), 'targetAttribute' => ['chapter_id' => 'chapter_id']],
            [['manga_id'], 'exist', 'skipOnError' => true, 'targetClass' => ar\Manga::className(), 'targetAttribute' => ['manga_id' => 'manga_id']],
            [['season_id'], 'exist', 'skipOnError' => true, 'targetClass' => ar\Season::className(), 'targetAttribute' => ['season_id' => 'season_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'task_id' => 'Task ID',
            'manga_id' => 'Manga ID',
            'season_id' => 'Season ID',
            'chapter_id' => 'Chapter ID',
            'task' => 'Task',
            'filename' => 'Filename',
            'status' => 'Status',
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
     * @return \yii\db\ActiveQuery
     */
    public function getManga()
    {
        return $this->hasOne(ar\Manga::className(), ['manga_id' => 'manga_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSeason()
    {
        return $this->hasOne(ar\Season::className(), ['season_id' => 'season_id']);
    }

    /**
     * @inheritdoc
     * @return CTaskQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CTaskQuery(get_called_class());
    }
}
