<?php
use yii\helpers\Html;
use app\models\ar\Season;
/**
 * Created by PhpStorm.
 * User: ZyManch
 * Date: 22.10.2016
 * Time: 15:49
 * @var Season[] $seasons
 * @var Season $season
 */
?>
<h3>Сезоны</h3>
<?php if (sizeof($seasons)>1):?>
<div class="btn-group-vertical manga-seasons">
    <? foreach ($seasons as $currentSeason):?>
    <?= Html::a(
        $currentSeason->title,
        $currentSeason->getUrl(),
        array(
            'class'=>'btn btn-block btn-default '.($currentSeason->season_id==$season->season_id?'active':'')
        )
    );?>
<? endforeach;?>
</div>
<?php endif;?>