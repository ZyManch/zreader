<?php
use \app\models\ar\Chapter;
/**
 * Created by PhpStorm.
 * User: ZyManch
 * Date: 23.10.2016
 * Time: 10:44
 * @var $lastChapters Chapter[][]
 */

?>
<h2>Последние обновления</h2>
<div  class="manga-list">
    <?php foreach ($lastChapters as $day => $seasons):?>
        <?php foreach($seasons as $seasonInfo):?>
            <?=$this->render('//manga/_item',array(
                'model' => $seasonInfo['season']->manga,
                'season' => $seasonInfo['season'],
                'chapters' => $seasonInfo['chapters']
            ));?>
        <?php endforeach;?>
    <?php endforeach;?>
</div>
