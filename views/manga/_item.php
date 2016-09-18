<?php
use yii\helpers\Html;
/**
 * Created by PhpStorm.
 * User: ZyManch
 * Date: 12.09.2016
 * Time: 18:59
 * @var $model \app\models\ar\Manga
 */
?>
<div class="manga">
    <div class="avatar" style="background-image: url('<?=$model->getImageUrl();?>')">
        
    </div>
    <div class="info">
        <div class="bg"></div>
        <div class="title">
            <?=Html::a($model->title,$model->getUrl());?>
        </div>
    </div>
</div>
