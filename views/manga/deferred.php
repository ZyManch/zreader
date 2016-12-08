<?php
use \yii\widgets\ListView;
/**
 * @var $this yii\web\View
 * @var $dataProvider \yii\data\ActiveDataProvider
 */
$this->title = 'Manga';
?>
<div class="row">

    <div class="col-md-12">

        <h2>Отложенное</h2>
        <div class="manga-list">
            <?= ListView::widget([
                'dataProvider' => $dataProvider,
                'itemView' => '_item',
                'layout' => "{items}\n{pager}"
            ]); ?>
        </div>



    </div>
</div>
