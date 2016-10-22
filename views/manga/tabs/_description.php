<?php
use yii\bootstrap\Button;
/**
 * Created by PhpStorm.
 * User: ZyManch
 * Date: 22.10.2016
 * Time: 10:52
 * @var $model \app\models\ar\Manga
 */

$parts = explode('<!-- separator -->', $model->description, 2);
echo $parts[0].' ';
if (sizeof($parts)>1):?>
    <?php echo Button::widget([
        'label' => 'show more',
        'options' => [
            'class' => 'btn-small btn-link',
            'onclick' => '$("#more-description").show();$(this).hide();'
        ],
    ]);?>
    <div style="display: none" id="more-description">
        <?php echo $parts[1];?>
    </div>
<?php endif; ?>