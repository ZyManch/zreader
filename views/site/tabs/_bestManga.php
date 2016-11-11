<?php
use app\models\ar\Manga;
/**
 * Created by PhpStorm.
 * User: ZyManch
 * Date: 23.10.2016
 * Time: 10:44
 * @var $bestMangas Manga[]
 */
?>
<h2>Топовые манги</h2>
<div class="manga-list">
    <?php foreach ($bestMangas as $manga):?>
        <?=$this->render('//manga/_item',array(
            'model' => $manga,
        ));?>
    <?php endforeach;?>
</div>
