<?php

namespace app\models\ar\_origin;

use Yii;
use app\models\ar;

/**
 * This is the model class for table "manga_has_genre".
 *
 * @property string $manga_has_genre_id
 * @property string $manga_id
 * @property string $genre_id
 *
 * @property ar\Genre\Model $genre
 * @property ar\Manga\Model $manga
 */
class CMangaHasGenre extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'manga_has_genre';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['manga_id', 'genre_id'], 'required'],
            [['manga_id', 'genre_id'], 'integer'],
            [['genre_id'], 'exist', 'skipOnError' => true, 'targetClass' => ar\Genre\Model::className(), 'targetAttribute' => ['genre_id' => 'genre_id']],
            [['manga_id'], 'exist', 'skipOnError' => true, 'targetClass' => ar\Manga\Model::className(), 'targetAttribute' => ['manga_id' => 'manga_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'manga_has_genre_id' => 'Manga Has Genre ID',
            'manga_id' => 'Manga ID',
            'genre_id' => 'Genre ID',
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
        public function getGenre()
    {
    return $this->hasOne(ar\Genre\Model::className(), ['genre_id' => 'genre_id']);
    }
        /**
     * @return \yii\db\ActiveQuery
     */
        public function getManga()
    {
    return $this->hasOne(ar\Manga\Model::className(), ['manga_id' => 'manga_id']);
    }
    
    /**
     * @inheritdoc
     * @return \app\models\ar\MangaHasGenre\Query the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\ar\MangaHasGenre\Query(get_called_class());
    }
}
