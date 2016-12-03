<?php
use app\models\ar\Manga;
use yii\helpers\Html;
/**
 * Created by PhpStorm.
 * User: ZyManch
 * Date: 22.10.2016
 * Time: 15:54
 * @var $model Manga\Model
 */
?>
<?= Html::img(
    $model->getImageUrl(),
    [
        'class'=>'avatar'
    ]
);?>