<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use \app\form\FilterForm;
use \app\models\ar\Genre;
use \app\models\ar\GenreType;
use \app\models\ar\Manga;
use \app\models\ar\Author;
use yii\widgets\ListView;
/**
 * @var $this yii\web\View
 * @var $model FilterForm
 */
$this->title = 'Каталог манги';
$years = array_combine(range(FilterForm::YEAR_FROM,date('Y')),range(FilterForm::YEAR_FROM,date('Y')));
krsort($years);
$genres = ArrayHelper::map(GenreType\Model::find()->all(),'title',function(GenreType\Model $genreType) {
    return ArrayHelper::map($genreType->getGenres()->all(),'genre_id','title');
});
?>
<div class="row">

    <div class="col-md-12">
        <h2><?=Html::encode($this->title);?></h2>

        <?php $form = ActiveForm::begin(['id' => 'filter-form','method'=>'get']); ?>
            <div class="row">
                <div class="col-md-4">
                    <?= $form->field($model, 'genres')->listBox(
                        $genres,
                        ['multiple'=>true,'size'=>12,'name'=>'genres']
                    ) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'declined_genres')->listBox(
                        $genres,
                        ['multiple'=>true,'size'=>12,'name'=>'declined_genres']
                    ) ?>
                </div>
                <div class="col-md-4">
                    <div>
                        <?= $form->field($model, 'author_id')->dropDownList(
                            ArrayHelper::map(Author\Model::find()->all(),'author_id','name'),
                            ['prompt'=>'Любой','name'=>'author_id']
                        ) ?>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                        <?= $form->field($model, 'year_from')->dropDownList(
                            $years,
                            ['prompt'=>'Любой','name'=>'year_from']
                        ) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'year_to')->dropDownList(
                                $years,
                                ['prompt'=>'Любой','name'=>'year_to']
                            ) ?>
                        </div>
                    </div>
                    <div>
                        <?= $form->field($model, 'is_finished',['inline'=>true])->radioList([
                            Manga\Model::IS_FINISHED_UNKNOWN => 'Любой',
                            Manga\Model::IS_FINISHED_YES => 'Завершен',
                            Manga\Model::IS_FINISHED_NO => 'Не завершен'
                        ]) ?>
                    </div>
                    <div class="form-group text-center">
                        <?= Html::submitButton('Найти', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                    </div>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        <div class="manga-list">
            <?= ListView::widget([
                'dataProvider' => $model->getProvider(),
                'itemView' => '_item',
                'layout' => "{items}\n{pager}"
            ]); ?>
        </div>

    </div>
</div>
