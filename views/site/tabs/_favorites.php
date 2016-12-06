<?php
use app\models\ar\Manga;
/**
 * Created by PhpStorm.
 * User: ZyManch
 * Date: 23.10.2016
 * Time: 10:44
 * @var $favorites Manga\Model[]
 */
?>
<?php if ($favorites):?>
    <div class="clearfix"></div>
    <h2>Избранное</h2>
    <div class="manga-list">
        <?php foreach ($favorites as $manga):?>
            <?=$this->render('//manga/_item',array(
                'model' => $manga,
            ));?>
        <?php endforeach;?>
    </div>
<?php endif;?>