<?php

use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $model app\models\ar\Genre */

$this->title = 'Манга жанра '.$model->title;
?>
<div class="manga-list">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= ListView::widget([
        'dataProvider' => $model->getMangaProvider(),
        'itemView' => '//manga/_item',
        'layout' => "{items}\n{pager}"
    ]); ?>

</div>
