<?php

namespace app\models\ar\_origin;

use Yii;
use app\models\ar;

/**
 * This is the model class for table "session_has_chapter".
 *
 * @property string $session_has_chapter_id
 * @property string $session_id
 * @property string $manga_id
 * @property string $chapter_from
 * @property string $chapter_to
 *
 * @property ar\Manga\Model $manga
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
            [['session_id', 'manga_id', 'chapter_from', 'chapter_to'], 'required'],
            [['session_id', 'manga_id'], 'integer'],
            [['chapter_from', 'chapter_to'], 'number'],
            [['manga_id'], 'exist', 'skipOnError' => true, 'targetClass' => ar\Manga\Model::className(), 'targetAttribute' => ['manga_id' => 'manga_id']],
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
            'manga_id' => 'Manga ID',
            'chapter_from' => 'Chapter From',
            'chapter_to' => 'Chapter To',
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
