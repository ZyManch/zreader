<?php
use yii\bootstrap\Button;
use app\models\ar\Manga;
/**
 * Created by PhpStorm.
 * User: ZyManch
 * Date: 22.10.2016
 * Time: 10:52
 * @var $model Manga\Model
 */
$authors = $model->getMangaHasAuthors()->all();
?>

<?php if ($authors):?>
<strong>Автор:</strong>
<?php echo implode(', ', array_map(function(\app\models\ar\MangaHasAuthor\Model $author) {
        return $author->author->name;
    }, $authors));?><br>
<?php endif;?>

<?php if ($model->created):?>
    <strong>Выпуск:</strong>
    <?php echo $model->created;?>
    <?php if ($model->finished && $model->finished != $model->created):?>
        - <?php echo $model->finished;?>
    <?php endif;?>
    <?php if ($model->is_finished == Manga\Model::IS_FINISHED_YES):?>
        (продолжается)
    <?php elseif ($model->is_finished == Manga\Model::IS_FINISHED_NO):?>
        (окончен)
    <?php endif;?>

    <br>
<?php endif;?>

    <br>
    <strong>Описание:</strong><br>
<?php
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