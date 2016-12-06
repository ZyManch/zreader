<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\bootstrap\NavBar;
use app\models\ar\Chapter;
use app\models\ar\Image;

/* @var $this yii\web\View */
/* @var $model app\models\ar\Chapter\Model */

$manga = $model->manga;
$this->registerJsFile('/js/manga-reader.js');
$this->registerCssFile('/css/manga-reader.css');
$this->registerJs('new MangaReader();');
$nextChapter = $model->getNextChapter();
?>



<?php
NavBar::begin([
    'brandLabel' => '',
    'brandUrl' => $manga->getUrl(),
    'options' => [
        'class' => 'navbar-inverse navbar-fixed-top',
    ],
]);
?>
<div class="navbar-form navbar-left">
    <div class="form-group">
        <?=Html::dropDownList(
            'chapter',
            $model->chapter_id,
            ArrayHelper::map($manga->getChapters()->orderBy('number')->all(),'chapter_id',function(Chapter\Model $data) {
                return $data->getTitle();
            }),
            array(
                'class'=>'form-control',
                'onchange'=>sprintf('location.href="%s".split("0").join($(this).val());',Url::to(array('read/view','id'=>'0','manga'=>$manga->url)))
            )
        );?>
    </div>
</div>
<?php
NavBar::end();
?>
<div class="manga-chapter">
    <?php foreach ($model->getGroupedImages() as $page => $images):?>
        <?php foreach ($images as $position => $image):?>
            <div class="manga-image image-<?=$image->type;?>"  data-width="<?=$image->width;?>" data-height="<?=$image->height;?>">
                <img src="<?=$image->getViewPath();?>"/>
            </div>
        <?php endforeach;?>
    <?php endforeach;?>
    <?php if ($nextChapter):?>
    <a class="manga-image" data-width="128" data-height="128" href="<?=Url::to($nextChapter->getUrl());?>">
        <img src="/manga/button/next.png"/>
    </a>
    <?php endif;?>
</div>
