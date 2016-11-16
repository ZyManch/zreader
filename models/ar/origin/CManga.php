<?php

namespace app\models\ar\origin;

use Yii;
use app\models\ar;

/**
 * This is the model class for table "manga".
 *
 * @property string $manga_id
 * @property string $author_id
 * @property string $title
 * @property string $url
 * @property string $original_title
 * @property string $description
 * @property string $is_finished
 * @property integer $created
 * @property integer $finished
 * @property string $views
 * @property string $reads
 *
 * @property ar\Author $author
 * @property ar\MangaHasGenre[] $ar\MangaHasGenres
 * @property ar\Season[] $ar\Seasons
 */
class CManga extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'manga';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['author_id', 'created', 'finished', 'views', 'reads'], 'integer'],
            [['title', 'url'], 'required'],
            [['description', 'is_finished'], 'string'],
            [['title', 'url', 'original_title'], 'string', 'max' => 128],
            [['title'], 'unique'],
            [['url'], 'unique'],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => ar\Author::className(), 'targetAttribute' => ['author_id' => 'author_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'manga_id' => 'Manga ID',
            'author_id' => 'Author ID',
            'title' => 'Title',
            'url' => 'Url',
            'original_title' => 'Original Title',
            'description' => 'Description',
            'is_finished' => 'Is Finished',
            'created' => 'Created',
            'finished' => 'Finished',
            'views' => 'Views',
            'reads' => 'Reads',
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(ar\Author::className(), ['author_id' => 'author_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMangaHasGenres()
    {
        return $this->hasMany(ar\MangaHasGenre::className(), ['manga_id' => 'manga_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSeasons()
    {
        return $this->hasMany(ar\Season::className(), ['manga_id' => 'manga_id']);
    }

    /**
     * @inheritdoc
     * @return CMangaQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CMangaQuery(get_called_class());
    }
}
