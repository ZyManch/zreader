<?php

namespace app\models\ar\origin;

use Yii;
use app\models\ar;

/**
 * This is the model class for table "manga".
 *
 * @property string $manga_id
 * @property string $title
 * @property string $url
 * @property string $english_title
 * @property string $original_title
 * @property string $description
 * @property string $is_reverted
 * @property string $is_finished
 * @property integer $created
 * @property integer $finished
 * @property string $views
 * @property string $reads
 *
 * @property ar\MangaHasAuthor[] $ar\MangaHasAuthors
 * @property ar\MangaHasGenre[] $ar\MangaHasGenres
 * @property ar\Season[] $ar\Seasons
 * @property ar\Task[] $ar\Tasks
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
            [['url'], 'required'],
            [['description', 'is_reverted', 'is_finished'], 'string'],
            [['created', 'finished', 'views', 'reads'], 'integer'],
            [['title'], 'string', 'max' => 200],
            [['url'], 'string', 'max' => 128],
            [['english_title', 'original_title'], 'string', 'max' => 256],
            [['url'], 'unique'],
            [['title'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'manga_id' => 'Manga ID',
            'title' => 'Title',
            'url' => 'Url',
            'english_title' => 'English Title',
            'original_title' => 'Original Title',
            'description' => 'Description',
            'is_reverted' => 'Is Reverted',
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
    public function getMangaHasAuthors()
    {
        return $this->hasMany(ar\MangaHasAuthor::className(), ['manga_id' => 'manga_id']);
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
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(ar\Task::className(), ['manga_id' => 'manga_id']);
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
