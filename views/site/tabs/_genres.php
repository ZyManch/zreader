<?php
use app\models\ar\Genre;
use yii\helpers\Html;
/**
 * Created by PhpStorm.
 * User: ZyManch
 * Date: 23.10.2016
 * Time: 10:46
 * @var $genres Genre[]
 */
?>
<h2>Жанры</h2>
    <?php foreach ($genres as $genre):?>
<div class="">
    <?=Html::a(
        ucfirst($genre->title),
        $genre->getUrl(),
        [
            'class'=>'btn btn-link'
        ]
    );?>
</div>
    <?php endforeach;?>

