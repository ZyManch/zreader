<?php
use \app\models\ar\Manga;
/**
 * Created by PhpStorm.
 * User: ZyManch
 * Date: 23.10.2016
 * Time: 10:44
 * @var $lastManga Manga\Model[]
 */

?>
<div class="clearfix"></div>
<h2>Последние обновления</h2>
<div  class="manga-list">
    <?php foreach ($lastManga as $manga):?>
        <?=$this->render('//manga/_item',array(
            'model' => $manga,
            'chapters' => $manga->getLastChapters()
        ));?>
    <?php endforeach;?>
</div>
