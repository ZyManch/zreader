<?php
use yii\helpers\Html;
use \app\models\ar;
use \app\models\ar\Chapter;
/**
 * Created by PhpStorm.
 * User: ZyManch
 * Date: 22.10.2016
 * Time: 11:10
 * @var $model ar\Manga\Model
 * @var $chapter Chapter\Model
 * @var $this \yii\web\View
 */
/** @var Chapter\Model $firstChapter */
$firstChapter = $model->getChapters()->orderBy('number')->one();
if (!$firstChapter) {
    return;
}
/** @var \app\models\session\ $session */
$session = Yii::$app->user->getSession();
$lastChapterNumber = $session->getMangaLastChapterNumber($model);
$nextChapter = null;
if (!is_null($lastChapterNumber)) {
    $nextChapter = $model->
        getChapters()->
        orderBy('number')->
        where('number>'.floatval($lastChapterNumber))->
        one();
}
?>
<br>
<br>
<div class="btn-group">
    <?=Html::a(
        'Начать читать',
        $firstChapter->getUrl(),
        array(
            'class'=>'btn btn-default'.($lastChapterNumber?'':' btn-warning')
        )
    );?>
    <?php if($lastChapterNumber):?>
        <?php if ($nextChapter):?>
            <?=Html::a(
                'Продолжить чтение',
                $nextChapter->getUrl(),
                array(
                    'class'=>'btn btn-default btn-success'
                )
            );?>
        <?php elseif ($model->is_finished == ar\Manga\Model::IS_FINISHED_YES) :?>
            <?=Html::a(
                'Чтение завершено',
                '#',
                array(
                    'class'=>'btn btn-default btn-warning',
                    'disabled' => 'disabled'
                )
            );?>
        <?php else:?>
            <?=Html::a(
                'Нет новых глав',
                '#',
                array(
                    'class'=>'btn btn-default btn-warning',
                    'disabled' => 'disabled'
                )
            );?>
        <?php endif;?>
    <?php endif;?>
</div>
