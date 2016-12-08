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
 * @var $this \yii\web\View
 * @var $model Manga\Model
 * @var $chapters
 */
if (!isset($chapters)) {
    $chapters = [];
}
/** @var \app\models\session\Settings $session */
$session = Yii::$app->user->getSession();
$status = $session->getMangaStatus($model);
?>
<?php if ($this->beginCache('manga',[
        'variations'=>[
            $model->manga_id,
            $chapters,
            $status
        ],
        'duration'=>3600
])):?>
    <?php
    $genres = array_map(function(MangaHasGenre\Model $genre) {
        return Html::a(
            $genre->genre->title,
            $genre->genre->getUrl()
        );
    }, $model->getMangaHasGenres()->joinWith('genre')->all());
    $url = Url::to($model->getUrl($chapters));
    ?>
    <div class="manga manga-<?=$status;?>">
        <div class="status">
            <?php switch($status):
                case SessionHasManga\Model::STATUS_FAVORITE:?>
                Избранное
                <?php break;
            case SessionHasManga\Model::STATUS_STARTED:?>
                Начатое
                <?php break;
            case SessionHasManga\Model::STATUS_DEFERRED:?>
                Отложенное
                <?php break;
            case SessionHasManga\Model::STATUS_HIDE:?>
                Скрытое
                <?php break;
            endswitch;?>
        </div>
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
                <ul class="menu-items dropdown-menu">
                    <li <?php if ($status == SessionHasManga\Model::STATUS_FAVORITE):?>class="active"<?php endif;?>>
                        <?=Html::a(
                            'Избранное',
                            [
                                    $status == SessionHasManga\Model::STATUS_FAVORITE ? 'session/mark-default' : 'session/mark-favorite',
                                    'manga'=>$model->url,
                                    'redirect' => Yii::$app->request->url
                            ]
                        );?>
                    </li>
                    <li <?php if ($status == SessionHasManga\Model::STATUS_STARTED):?>class="active"<?php endif;?>>
                        <?=Html::a(
                            'Начатое',
                            [
                                $status == SessionHasManga\Model::STATUS_STARTED ? 'session/mark-default' : 'session/mark-started',
                                'manga'=>$model->url,
                                'redirect' => Yii::$app->request->url
                            ]
                        );?>
                    </li>
                    <li <?php if ($status == SessionHasManga\Model::STATUS_DEFERRED):?>class="active"<?php endif;?>>
                        <?=Html::a(
                            'Отложенное',
                            [
                                $status == SessionHasManga\Model::STATUS_DEFERRED ? 'session/mark-default' : 'session/mark-deferred',
                                'manga'=>$model->url,
                                'redirect' => Yii::$app->request->url
                            ]
                        );?>
                    </li>
                    <li <?php if ($status == SessionHasManga\Model::STATUS_HIDE):?>class="active"<?php endif;?>>
                        <?=Html::a(
                            'Скрытое',
                            [
                                    $status == SessionHasManga\Model::STATUS_HIDE ? 'session/mark-default' : 'session/mark-hidden',
                                    'manga'=>$model->url,
                                    'redirect' => Yii::$app->request->url
                            ]
                        );?>
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
    <?php $this->endCache();?>
<?php endif;?>
