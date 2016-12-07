<?php

use yii\helpers\Html;
use yii\bootstrap\Tabs;

/* @var $this yii\web\View */
/* @var $model app\models\ar\Manga\Model */

$this->title = $model->title;
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
        <?=$this->render('tabs/_chapters',['model'=>$model]);?>
    </div>
</div>
