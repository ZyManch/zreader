<?php

namespace app\models\ar\origin;

use Yii;
use app\models\ar;

/**
 * This is the model class for table "user".
 *
 * @property string $user_id
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string $created
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
            [['username', 'email', 'password'], 'required'],
            [['created'], 'safe'],
            [['username', 'password'], 'string', 'max' => 64],
            [['email'], 'string', 'max' => 128],
            [['username'], 'unique'],
            [['email'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'username' => 'Username',
            'email' => 'Email',
            'password' => 'Password',
            'created' => 'Created',
        ];
    }

    /**
     * @inheritdoc
     * @return CUserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CUserQuery(get_called_class());
    }
}
