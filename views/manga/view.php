<?php

use yii\helpers\Html;
use yii\bootstrap\Tabs;

/* @var $this yii\web\View */
/* @var $model app\models\ar\Manga\Model */
/* @var $season app\models\ar\Season\Model */
/* @var $currentSeason app\models\ar\Season\Model */

$this->title = $model->title;
$seasons = $model->getSeasons()->orderBy('position')->all();
if (sizeof($seasons)>1) {
    $this->title.=' ('.$season->title.')';
}
?>
<div class="row">
    <div class="col-xs-12">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
</div>

<div class="row manga-view">
    <div class="col-xs-12">

        <?=$this->render('tabs/_avatar',['model'=>$model]);?>
        <?=$this->render('tabs/_description',['model'=>$model]);?>
        <?=$this->render('tabs/_seasons',['seasons'=>$seasons,'season'=>$season]);?>
    </div>
</div>

<div class="row manga-view">
    <div class="col-xs-12">
        <?=$this->render('tabs/_chapters',['model'=>$season]);?>
    </div>



</div>
