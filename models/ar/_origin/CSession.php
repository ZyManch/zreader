<?php

namespace app\models\ar\_origin;

use Yii;
use app\models\ar;

/**
 * This is the model class for table "session".
 *
 * @property string $session_id
 * @property string $cookie_hash
 * @property string $created
 * @property string $last_visit
 *
 * @property ar\SessionHasManga\Model[] $ar\SessionHasManga\Models
 * @property ar\User\Model[] $ar\User\Models
 */
class CSession extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'session';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cookie_hash'], 'required'],
            [['created', 'last_visit'], 'safe'],
            [['cookie_hash'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'session_id' => 'Session ID',
            'cookie_hash' => 'Cookie Hash',
            'created' => 'Created',
            'last_visit' => 'Last Visit',
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
        public function getSessionHasMangas()
    {
        return $this->hasMany(ar\SessionHasManga\Model::className(), ['session_id' => 'session_id']);
    }
        /**
     * @return \yii\db\ActiveQuery
     */
        public function getUsers()
    {
        return $this->hasMany(ar\User\Model::className(), ['session_id' => 'session_id']);
    }
    
    /**
     * @inheritdoc
     * @return \app\models\ar\Session\Query the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\ar\Session\Query(get_called_class());
    }
}
