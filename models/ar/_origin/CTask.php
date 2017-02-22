<?php

namespace app\models\ar\_origin;

use Yii;
use app\models\ar;

/**
 * This is the model class for table "task".
 *
 * @property string $task_id
 * @property string $manga_id
 * @property string $chapter_id
 * @property string $task
 * @property string $storage_id
 * @property string $filename
 * @property string $status
 *
 * @property ar\Chapter\Model $chapter
 * @property ar\Manga\Model $manga
 * @property ar\Storage\Model $storage
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
            [['manga_id', 'chapter_id', 'storage_id'], 'integer'],
            [['task', 'storage_id', 'filename'], 'required'],
            [['status'], 'string'],
            [['task'], 'string', 'max' => 16],
            [['filename'], 'string', 'max' => 256],
            [['chapter_id'], 'exist', 'skipOnError' => true, 'targetClass' => ar\Chapter\Model::className(), 'targetAttribute' => ['chapter_id' => 'chapter_id']],
            [['manga_id'], 'exist', 'skipOnError' => true, 'targetClass' => ar\Manga\Model::className(), 'targetAttribute' => ['manga_id' => 'manga_id']],
            [['storage_id'], 'exist', 'skipOnError' => true, 'targetClass' => ar\Storage\Model::className(), 'targetAttribute' => ['storage_id' => 'storage_id']],
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
            'chapter_id' => 'Chapter ID',
            'task' => 'Task',
            'storage_id' => 'Storage ID',
            'filename' => 'Filename',
            'status' => 'Status',
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
        public function getManga()
    {
    return $this->hasOne(ar\Manga\Model::className(), ['manga_id' => 'manga_id']);
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
     * @return \app\models\ar\Task\Query the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\ar\Task\Query(get_called_class());
    }
}
