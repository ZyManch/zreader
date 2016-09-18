<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\bootstrap\NavBar;
use app\models\ar\Chapter;

/* @var $this yii\web\View */
/* @var $model app\models\ar\Chapter */

$season = $model->season;
$manga = $season->manga;
?>



<?php
NavBar::begin([
    'brandLabel' => $season->title,
    'brandUrl' => $season->getUrl(),
    'options' => [
        'class' => 'navbar-inverse navbar-fixed-top',
    ],
]);
?>
<div class="navbar-form navbar-left">
    <div class="form-group">
        <?php echo Html::dropDownList(
            'chapter',
            $model->chapter_id,
            ArrayHelper::map($season->getChapters()->orderBy('number')->all(),'chapter_id',function(Chapter $data) {
                return $data->getTitle();
            }),
            array(
                'class'=>'form-control',
                'onchange'=>sprintf('location.href="%s".split("0").join($(this).val());',Url::to(array('read/view','id'=>'0','manga'=>$manga->url)))
            )
        );?>
    </div>
</div>
<?php
NavBar::end();
?>

