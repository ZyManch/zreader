<?php

use yii\helpers\Html;
use yii\bootstrap\Button;

/* @var $this yii\web\View */
/* @var $model app\models\ar\Manga */
/* @var $season app\models\ar\Season */

$this->title = $model->title;
?>
<div class="row">
    <div class="col-xs-12">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
</div>

<div class="row">
    <div class="col-md-3 text-center">
        <?= Html::img(
            $model->getImageUrl()
        );?>
    </div>
    <div class="col-md-6">
        <?php
        $parts = explode('<!-- separator -->', $model->description, 2);
        echo $parts[0].' ';
        if (sizeof($parts)>1):?>
            <?php echo Button::widget([
                'label' => 'show more',
                'options' => [
                    'class' => 'btn-small btn-link',
                    'onclick' => '$("#more-description").show();$(this).hide();'
                ],
            ]);?>
            <div style="display: none" id="more-description">
                <?php echo $parts[1];?>
            </div>
        <?php endif; ?>
        <hr>
        <h3>Сезоны</h3>
        <ul>
        <?php foreach ($model->getSeasons()->orderBy('position')->all() as $season):?>
            <li><?=Html::a($season->title,$season->getUrl());?></li>
        <?php endforeach;?>
        </ul>
    </div>



</div>
