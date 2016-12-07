<?php

namespace app\models\ar\_origin;

use Yii;
use app\models\ar;

/**
 * This is the model class for table "genre".
 *
 * @property string $genre_id
 * @property string $title
 * @property string $url
 * @property integer $power
 * @property string $genre_type_id
 *
 * @property ar\GenreType\Model $genreType
 * @property ar\MangaHasGenre\Model[] $ar\MangaHasGenre\Models
 */
class CGenre extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'genre';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'url', 'genre_type_id'], 'required'],
            [['power', 'genre_type_id'], 'integer'],
            [['title', 'url'], 'string', 'max' => 128],
            [['genre_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => ar\GenreType\Model::className(), 'targetAttribute' => ['genre_type_id' => 'genre_type_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'genre_id' => 'Genre ID',
            'title' => 'Title',
            'url' => 'Url',
            'power' => 'Power',
            'genre_type_id' => 'Genre Type ID',
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
        public function getGenreType()
    {
    return $this->hasOne(ar\GenreType\Model::className(), ['genre_type_id' => 'genre_type_id']);
    }
        /**
     * @return \yii\db\ActiveQuery
     */
        public function getMangaHasGenres()
    {
        return $this->hasMany(ar\MangaHasGenre\Model::className(), ['genre_id' => 'genre_id']);
    }
    
    /**
     * @inheritdoc
     * @return \app\models\ar\Genre\Query the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\ar\Genre\Query(get_called_class());
    }
}
