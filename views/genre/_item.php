<?php
use yii\helpers\Html;
use app\models\ar\Genre;
/**
 * Created by PhpStorm.
 * User: ZyManch
 * Date: 23.10.2016
 * Time: 12:03
 * @var $model Genre\Model
 */
?>
<?=Html::a(
    ucfirst($model->title),
    $model->getUrl()
);?>
