<?php
use yii\helpers\Html;
use yii\helpers\Url;
use \app\models\ar\MangaHasGenre;
/**
 * Created by PhpStorm.
 * User: ZyManch
 * Date: 12.09.2016
 * Time: 18:59
 * @var $model \app\models\ar\Manga
 */
$genres = array_map(function(MangaHasGenre $genre) {
    return Html::a(
        $genre->genre->title,
        array('genre/view','genre'=>$genre->genre->url)
    );
}, $model->getMangaHasGenres()->all());

?>
<div class="manga">
    <a class="avatar" style="background-image: url('<?=$model->getImageUrl();?>')" href="<?=Url::to($model->getUrl());?>">
        
    </a>
    <div class="info">
        <?php if ($genres):?>
        <div class="genres">
            <?=implode(', ',$genres);?>
        </div>
        <?php endif;?>
        <div class="bg"></div>
        <div class="title">
            <?=Html::a($model->title,$model->getUrl());?>
        </div>
    </div>
</div>
