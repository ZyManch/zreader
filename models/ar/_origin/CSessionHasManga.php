<?php

namespace app\models\ar\_origin;

use Yii;
use app\models\ar;

/**
 * This is the model class for table "session_has_manga".
 *
 * @property string $session_has_manga_id
 * @property string $session_id
 * @property string $manga_id
 * @property string $status
 * @property string $is_read_finished
 *
 * @property ar\Manga\Model $manga
 * @property ar\Session\Model $session
 */
class CSessionHasManga extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'session_has_manga';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['session_id', 'manga_id'], 'required'],
            [['session_id', 'manga_id'], 'integer'],
            [['status', 'is_read_finished'], 'string'],
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
            'session_has_manga_id' => 'Session Has Manga ID',
            'session_id' => 'Session ID',
            'manga_id' => 'Manga ID',
            'status' => 'Status',
            'is_read_finished' => 'Is Read Finished',
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
     * @return \app\models\ar\SessionHasManga\Query the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\ar\SessionHasManga\Query(get_called_class());
    }
}
