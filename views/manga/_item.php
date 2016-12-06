<?php
use yii\helpers\Html;
use yii\helpers\Url;
use \app\models\ar\MangaHasGenre;
use \app\models\ar\SessionHasManga;
use \app\models\ar\Manga;
/**
 * Created by PhpStorm.
 * User: ZyManch
 * Date: 12.09.2016
 * Time: 18:59
 * @var $model Manga\Model
 * @var $chapters
 */
$cache = \Yii::$app->cache;
if (isset($cache['genre_'.$model->manga_id])) {
    $genres = $cache['genre_'.$model->manga_id];
} else {
    $genres = array_map(function(MangaHasGenre\Model $genre) {
        return Html::a(
            $genre->genre->title,
            $genre->genre->getUrl()
        );
    }, $model->getMangaHasGenres()->with('genre')->all());
    $cache->set('genre_'.$model->manga_id, $genres, 3600);
}

if (!isset($chapters)) {
    $chapters = [];
}
$url = Url::to($model->getUrl($chapters));
/** @var \app\models\Session $session */
$session = Yii::$app->user->getSession();
$status = $session->getMangaStatus($model);
?>
<div class="manga manga-<?=$status;?>">
    <a class="avatar" style="background-image: url('<?=$model->getImageUrl();?>')" href="<?=$url;?>">
        
    </a>
    <div class="info">
        <?php if ($genres):?>
        <div class="genres">
            <?=implode(', ',$genres);?>
        </div>
        <?php endif;?>
        <div class="bg"></div>
        <div class="menu">
            <div class="glyphicon glyphicon-cog"></div>
            <ul class="menu-items">

                <li>
                    <?php if ($status == SessionHasManga\Model::STATUS_FAVORITE):?>
                        <?=Html::a(
                            'Скрыть',
                            ['manga/exclude','manga'=>$model->url,'redirect' => Yii::$app->request->url]
                        );?>
                    <?php else:?>
                        <?=Html::a(
                            'Показать',
                            ['manga/show','manga'=>$model->url,'redirect' => Yii::$app->request->url]
                        );?>
                    <?php endif;?>
                </li>
                <li>
                    <?php if ($status == SessionHasManga\Model::STATUS_FAVORITE):?>
                        <?=Html::a(
                            'Из избранного',
                            ['manga/show','manga'=>$model->url,'redirect' => Yii::$app->request->url]
                        );?>
                    <?php else:?>
                        <?=Html::a(
                            'В избранное',
                            ['manga/favorite','manga'=>$model->url,'redirect' => Yii::$app->request->url]
                        );?>
                    <?php endif;?>
                </li>
            </ul>
        </div>
        <div class="title">
            <?=Html::a(
                $model->title.($chapters?'<br>Серии ':'').implode(',',$chapters),
                $url
            );?>
        </div>
    </div>
</div>
