<?php

namespace app\models\ar\_origin;

use Yii;
use app\models\ar;

/**
 * This is the model class for table "task_dump".
 *
 * @property string $task_id
 * @property string $manga_id
 * @property string $chapter_id
 * @property string $task
 * @property string $filename
 * @property string $status
 */
class CTaskDump extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'task_dump';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['manga_id', 'chapter_id'], 'integer'],
            [['task', 'filename'], 'required'],
            [['status'], 'string'],
            [['task'], 'string', 'max' => 16],
            [['filename'], 'string', 'max' => 256],
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
            'filename' => 'Filename',
            'status' => 'Status',
        ];
    }

    /**
     * @inheritdoc
     * @return \app\models\ar\TaskDump\Query the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\ar\TaskDump\Query(get_called_class());
    }
}
