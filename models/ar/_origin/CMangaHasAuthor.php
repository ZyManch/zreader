<?php

namespace app\models\ar\_origin;

use Yii;
use app\models\ar;

/**
 * This is the model class for table "manga_has_author".
 *
 * @property string $manga_has_author_id
 * @property string $manga_id
 * @property string $author_id
 *
 * @property ar\Author\Model $author
 * @property ar\Manga\Model $manga
 */
class CMangaHasAuthor extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'manga_has_author';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['manga_id', 'author_id'], 'required'],
            [['manga_id', 'author_id'], 'integer'],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => ar\Author\Model::className(), 'targetAttribute' => ['author_id' => 'author_id']],
            [['manga_id'], 'exist', 'skipOnError' => true, 'targetClass' => ar\Manga\Model::className(), 'targetAttribute' => ['manga_id' => 'manga_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'manga_has_author_id' => 'Manga Has Author ID',
            'manga_id' => 'Manga ID',
            'author_id' => 'Author ID',
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
        public function getAuthor()
    {
    return $this->hasOne(ar\Author\Model::className(), ['author_id' => 'author_id']);
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
     * @return \app\models\ar\MangaHasAuthor\Query the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\ar\MangaHasAuthor\Query(get_called_class());
    }
}
