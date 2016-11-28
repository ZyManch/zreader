<?php
use yii\helpers\Html;
use yii\helpers\Url;
use \app\models\ar\MangaHasGenre;
use \app\models\ar\Manga;
use \app\models\ar\Season;
/**
 * Created by PhpStorm.
 * User: ZyManch
 * Date: 12.09.2016
 * Time: 18:59
 * @var $model Manga
 * @var $season Season
 * @var $chapters
 */
$genres = array_map(function(MangaHasGenre $genre) {
    return Html::a(
        $genre->genre->title,
        $genre->genre->getUrl()
    );
}, $model->getMangaHasGenres()->all());
if (!isset($chapters)) {
    $chapters = [];
}
if (isset($season)) {
    $url = Url::to($season->getUrl($chapters));
} else {
    $url = Url::to($model->getUrl($chapters));
}
?>
<div class="manga">
    <a class="avatar" style="background-image: url('<?=$model->getImageUrl();?>')" href="<?=$url;?>">
        
    </a>
    <div class="info">
        <?php if ($genres):?>
        <div class="genres">
            <?=implode(', ',$genres);?>
        </div>
        <?php endif;?>
        <div class="bg"></div>
        <div class="title">
            <?=Html::a(
                (isset($season) ? $season->getFullTitle() : $model->title).($chapters?'<br>Серии ':'').implode(',',$chapters),
                $url
            );?>
        </div>
    </div>
</div>
