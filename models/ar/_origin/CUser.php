<?php

namespace app\models\ar\_origin;

use Yii;
use app\models\ar;

/**
 * This is the model class for table "user".
 *
 * @property string $user_id
 * @property string $session_id
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string $created
 *
 * @property ar\Session\Model $session
 */
class CUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['session_id', 'username', 'email', 'password'], 'required'],
            [['session_id'], 'integer'],
            [['created'], 'safe'],
            [['username', 'password'], 'string', 'max' => 64],
            [['email'], 'string', 'max' => 128],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['session_id'], 'exist', 'skipOnError' => true, 'targetClass' => ar\Session\Model::className(), 'targetAttribute' => ['session_id' => 'session_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'session_id' => 'Session ID',
            'username' => 'Username',
            'email' => 'Email',
            'password' => 'Password',
            'created' => 'Created',
        ];
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
     * @return \app\models\ar\User\Query the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\ar\User\Query(get_called_class());
    }
}
