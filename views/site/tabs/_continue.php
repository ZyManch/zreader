<?php
use app\models\ar\Manga;
/**
 * Created by PhpStorm.
 * User: ZyManch
 * Date: 23.10.2016
 * Time: 10:44
 * @var $continue Manga\Model[]
 */
?>
<?php if ($continue):?>
    <div class="clearfix"></div>
    <h2>Недочитанное</h2>
    <div class="manga-list">
        <?php foreach ($continue as $manga):?>
            <?=$this->render('//manga/_item',array(
                'model' => $manga,
            ));?>
        <?php endforeach;?>
    </div>
<?php endif;?>