<?php

namespace app\models\ar\_origin;

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
 * @property ar\Chapter\Model[] $ar\Chapter\Models
 * @property ar\Manga\Model $manga
 * @property ar\SessionHasChapter\Model[] $ar\SessionHasChapter\Models
 * @property ar\Task\Model[] $ar\Task\Models
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
            [['manga_id'], 'exist', 'skipOnError' => true, 'targetClass' => ar\Manga\Model::className(), 'targetAttribute' => ['manga_id' => 'manga_id']],
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
        return $this->hasMany(ar\Chapter\Model::className(), ['season_id' => 'season_id']);
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
        public function getSessionHasChapters()
    {
        return $this->hasMany(ar\SessionHasChapter\Model::className(), ['season_id' => 'season_id']);
    }
        /**
     * @return \yii\db\ActiveQuery
     */
        public function getTasks()
    {
        return $this->hasMany(ar\Task\Model::className(), ['season_id' => 'season_id']);
    }
    
    /**
     * @inheritdoc
     * @return \app\models\ar\Season\Query the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\ar\Season\Query(get_called_class());
    }
}
