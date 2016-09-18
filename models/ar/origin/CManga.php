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
 * @property string $original_title
 * @property string $description
 * @property string $views
 * @property string $reads
 *
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
            [['title', 'url'], 'required'],
            [['description'], 'string'],
            [['views', 'reads'], 'integer'],
            [['title', 'url', 'original_title'], 'string', 'max' => 128],
            [['title'], 'unique'],
            [['url'], 'unique'],
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
            'original_title' => 'Original Title',
            'description' => 'Description',
            'views' => 'Views',
            'reads' => 'Reads',
        ];
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
