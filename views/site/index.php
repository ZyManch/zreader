<?php
use app\models\ar\Chapter;
use app\models\ar\Manga;
use app\models\ar\Genre;
use yii\helpers\Html;
/**
 * @var $this yii\web\View
 * @var $lastManga Manga\Model[]
 * @var $bestMangas Manga\Model[]
 * @var $genres Genre\Model[]
 * @var $favorites Manga\Model[]
 */
$this->title = 'Manga';
?>
<div class="row">

    <div class="col-md-10">

        <?=$this->render('tabs/_favorites',['favorites'=>$favorites]);?>
        <?=$this->render('tabs/_lastChanges',['lastManga'=>$lastManga]);?>
        <?=$this->render('tabs/_bestManga',['bestMangas'=>$bestMangas]);?>


    </div>
    <div class="col-md-2">
        <?=$this->render('tabs/_genres',['genres'=>$genres]);?>

    </div>

    <div class="col-md-12">


    </div>
</div>
