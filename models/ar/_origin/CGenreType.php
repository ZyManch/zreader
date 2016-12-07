<?php

namespace app\models\ar\_origin;

use Yii;
use app\models\ar;

/**
 * This is the model class for table "genre_type".
 *
 * @property string $genre_type_id
 * @property string $title
 *
 * @property ar\Genre\Model[] $ar\Genre\Models
 */
class CGenreType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'genre_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['title'], 'string', 'max' => 128],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'genre_type_id' => 'Genre Type ID',
            'title' => 'Title',
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
        public function getGenres()
    {
        return $this->hasMany(ar\Genre\Model::className(), ['genre_type_id' => 'genre_type_id']);
    }
    
    /**
     * @inheritdoc
     * @return \app\models\ar\GenreType\Query the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\ar\GenreType\Query(get_called_class());
    }
}
