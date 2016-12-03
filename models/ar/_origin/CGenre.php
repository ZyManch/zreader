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
 *
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
            [['title', 'url'], 'required'],
            [['title', 'url'], 'string', 'max' => 128],
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
        ];
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
