<?php
use app\models\ar\Chapter;
use app\models\ar\Manga;
use app\models\ar\Genre;
use yii\helpers\Html;
/**
 * @var $this yii\web\View
 * @var $lastChapters Chapter[]
 * @var $bestMangas Manga[]
 * @var $genres Genre[]
 */
$this->title = 'Manga';
?>
<div class="row">

    <div class="col-md-10">

        <?=$this->render('tabs/_lastChanges',['lastChapters'=>$lastChapters]);?>
        <?=$this->render('tabs/_bestManga',['bestMangas'=>$bestMangas]);?>


    </div>
    <div class="col-md-2">
        <?=$this->render('tabs/_genres',['genres'=>$genres]);?>

    </div>

    <div class="col-md-12">


    </div>
</div>
