<?php

namespace app\models\ar\_origin;

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
 * @property string $changed
 * @property string $chapters
 * @property string $views
 * @property string $reads
 *
 * @property ar\Chapter\Model[] $ar\Chapter\Models
 * @property ar\MangaHasAuthor\Model[] $ar\MangaHasAuthor\Models
 * @property ar\MangaHasGenre\Model[] $ar\MangaHasGenre\Models
 * @property ar\SessionHasChapter\Model[] $ar\SessionHasChapter\Models
 * @property ar\SessionHasManga\Model[] $ar\SessionHasManga\Models
 * @property ar\Task\Model[] $ar\Task\Models
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
            [['url', 'changed'], 'required'],
            [['description', 'is_reverted', 'is_finished'], 'string'],
            [['created', 'finished', 'chapters', 'views', 'reads'], 'integer'],
            [['changed'], 'safe'],
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
            'changed' => 'Changed',
            'chapters' => 'Chapters',
            'views' => 'Views',
            'reads' => 'Reads',
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
        public function getChapters()
    {
        return $this->hasMany(ar\Chapter\Model::className(), ['manga_id' => 'manga_id']);
    }
        /**
     * @return \yii\db\ActiveQuery
     */
        public function getMangaHasAuthors()
    {
        return $this->hasMany(ar\MangaHasAuthor\Model::className(), ['manga_id' => 'manga_id']);
    }
        /**
     * @return \yii\db\ActiveQuery
     */
        public function getMangaHasGenres()
    {
        return $this->hasMany(ar\MangaHasGenre\Model::className(), ['manga_id' => 'manga_id']);
    }
        /**
     * @return \yii\db\ActiveQuery
     */
        public function getSessionHasChapters()
    {
        return $this->hasMany(ar\SessionHasChapter\Model::className(), ['manga_id' => 'manga_id']);
    }
        /**
     * @return \yii\db\ActiveQuery
     */
        public function getSessionHasMangas()
    {
        return $this->hasMany(ar\SessionHasManga\Model::className(), ['manga_id' => 'manga_id']);
    }
        /**
     * @return \yii\db\ActiveQuery
     */
        public function getTasks()
    {
        return $this->hasMany(ar\Task\Model::className(), ['manga_id' => 'manga_id']);
    }
    
    /**
     * @inheritdoc
     * @return \app\models\ar\Manga\Query the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\ar\Manga\Query(get_called_class());
    }
}
