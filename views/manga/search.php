<?php
use app\models\ar\Chapter;
use app\models\ar\Manga;
use app\models\ar\Genre;
use yii\helpers\Html;
/**
 * @var $this yii\web\View
 * @var $mangas Genre[]
 * @var $search
 */
$this->title = 'Поиск по "'.$search.'"';
?>
<div class="row">

    <div class="col-md-12">
        <h2><?=Html::encode($this->title);?></h2>
        <div class="manga-list">
            <?php foreach ($mangas as $manga):?>
                <?=$this->render('//manga/_item',array(
                    'model' => $manga,
                ));?>
            <?php endforeach;?>
        </div>

    </div>
</div>
