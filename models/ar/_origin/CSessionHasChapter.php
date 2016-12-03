<?php

namespace app\models\ar\_origin;

use Yii;
use app\models\ar;

/**
 * This is the model class for table "session_has_chapter".
 *
 * @property string $session_has_chapter_id
 * @property string $session_id
 * @property string $season_id
 * @property string $chapter_from
 * @property string $chapter_to
 *
 * @property ar\Season\Model $season
 * @property ar\Session\Model $session
 */
class CSessionHasChapter extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'session_has_chapter';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['session_id', 'season_id', 'chapter_from', 'chapter_to'], 'required'],
            [['session_id', 'season_id'], 'integer'],
            [['chapter_from', 'chapter_to'], 'number'],
            [['season_id'], 'exist', 'skipOnError' => true, 'targetClass' => ar\Season\Model::className(), 'targetAttribute' => ['season_id' => 'season_id']],
            [['session_id'], 'exist', 'skipOnError' => true, 'targetClass' => ar\Session\Model::className(), 'targetAttribute' => ['session_id' => 'session_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'session_has_chapter_id' => 'Session Has Chapter ID',
            'session_id' => 'Session ID',
            'season_id' => 'Season ID',
            'chapter_from' => 'Chapter From',
            'chapter_to' => 'Chapter To',
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
        public function getSession()
    {
    return $this->hasOne(ar\Session\Model::className(), ['session_id' => 'session_id']);
    }
    
    /**
     * @inheritdoc
     * @return \app\models\ar\SessionHasChapter\Query the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\ar\SessionHasChapter\Query(get_called_class());
    }
}
