<?php

namespace app\models\ar\origin;

use Yii;
use app\models\ar;

/**
 * This is the model class for table "author".
 *
 * @property string $author_id
 * @property string $name
 * @property string $avatar
 *
 * @property ar\MangaHasAuthor[] $ar\MangaHasAuthors
 */
class CAuthor extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'author';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name', 'avatar'], 'string', 'max' => 128],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'author_id' => 'Author ID',
            'name' => 'Name',
            'avatar' => 'Avatar',
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMangaHasAuthors()
    {
        return $this->hasMany(ar\MangaHasAuthor::className(), ['author_id' => 'author_id']);
    }

    /**
     * @inheritdoc
     * @return CAuthorQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CAuthorQuery(get_called_class());
    }
}
