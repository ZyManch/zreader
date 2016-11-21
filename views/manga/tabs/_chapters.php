<?php
use yii\helpers\Html;
use \app\models\ar\Season;
use \app\models\ar\Chapter;
/**
 * Created by PhpStorm.
 * User: ZyManch
 * Date: 22.10.2016
 * Time: 11:10
 * @var $model Season
 * @var $chapter Chapter
 * @var $this \yii\web\View
 */
$chapters = $model->getChapters()->orderBy('number')->all();
if (!$chapters) {
    return;
}
$this->registerJs(
    'var $groupedBlock = $(".manga-grouped-chapters"),
        $chaptersBlock = $(".manga-chapters");
    $groupedBlock.find("button").click(function() {
        var $this = $(this);
        $groupedBlock.find(".active").removeClass("active");
        $chaptersBlock.removeClass("manga-chapters-visible");
        $this.addClass("active");
        $("#chapters-"+$this.data("group")).addClass("manga-chapters-visible");
    });'
);
$groupedChapters = [];
foreach ($chapters as $chapter) {
    $groupId = floor($chapter->number/Chapter::CHAPTER_GROUP_COUNT)*Chapter::CHAPTER_GROUP_COUNT;
    if (!isset($groupedChapters[$groupId])) {
        $groupedChapters[$groupId] = [
            'min' => floor($chapter->number),
            'max' => floor($chapter->number),
            'chapters'=>[]
        ];
    }
    $groupedChapters[$groupId]['chapters'][] = $chapter;
    $groupedChapters[$groupId]['max'] = floor($chapter->number);
}
$min = reset($chapters)->number;
$max = end($chapters)->number;
$start = floor($min/Chapter::CHAPTER_GROUP_COUNT)*Chapter::CHAPTER_GROUP_COUNT;
$isFirst = true;
?>

<?php if (sizeof($groupedChapters) > 1):?>
    <h3>Серии по группам</h3>

    <div class="btn-group manga-grouped-chapters">
    <?php foreach ($groupedChapters as $groupId => $groupedChapter):?>
        <?=Html::button(
            'С '.$groupedChapter['min'].' по '.$groupedChapter['max'],
            [
                'class'=>'btn btn-default'.($isFirst?' active':''),
                'data-group'=>$groupId
            ]
        );?>
        <?php $isFirst=false;?>
    <?php endforeach;?>
    </div>
<?php endif;?>


<h3>Серий</h3>
<?php $isFirst=true;?>
<?php foreach ($groupedChapters as $groupId => $groupedChapter):?>
<div class="manga-chapters <?php if ($isFirst):?>manga-chapters-visible<?php endif;?>" id="chapters-<?=$groupId;?>">
    <?php foreach ($groupedChapter['chapters'] as $chapter):?>
        <?=Html::a(
            $chapter->getTitle(),
            $chapter->getUrl(),
            array(
                'class'=>'btn btn-default'
            )
        );?>
    <?php endforeach;?>
    <?php $isFirst=false;?>
</div>
<?php endforeach;?>


