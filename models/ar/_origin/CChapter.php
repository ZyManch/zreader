<?php

namespace app\models\ar\_origin;

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
 * @property ar\Season\Model $season
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
            [['season_id', 'number'], 'required'],
            [['season_id'], 'integer'],
            [['number'], 'number'],
            [['created'], 'safe'],
            [['title'], 'string', 'max' => 250],
            [['season_id'], 'exist', 'skipOnError' => true, 'targetClass' => ar\Season\Model::className(), 'targetAttribute' => ['season_id' => 'season_id']],
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
    return $this->hasOne(ar\Season\Model::className(), ['season_id' => 'season_id']);
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
