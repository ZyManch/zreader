<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ar\Season */
/* @var $chapter app\models\ar\Chapter */

$this->title = $model->title;
$manga = $model->manga;
?>

<div class="row">
    <div class="col-xs-12">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
</div>

<div class="row">
    <div class="col-md-3 text-center">
        <?= Html::img(
            $manga->getImageUrl()
        );?>
        <br>
        <?=Html::a('Список сезонов',$manga->getUrl());?>
    </div>
    <div class="col-md-6">
        <h3>Список серий</h3>
        <ul>
            <?php foreach ($model->getChapters()->orderBy('number')->all() as $chapter):?>
                <li><?=Html::a($chapter->getTitle(),$chapter->getUrl());?></li>
            <?php endforeach;?>
        </ul>
    </div>



</div>
