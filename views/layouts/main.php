<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\jui\AutoComplete;
use yii\web\JsExpression;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->params['brandName'],
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            ['label' => 'КАТАЛОГ', 'url' => ['manga/index']],
            ['label' => 'ЖАНРЫ', 'url' => ['genre/index']],
            Yii::$app->user->isGuest ? (
                ['label' => 'ЛОГИН', 'url' => ['site/login']]
            ) : (
                '<li>'
                . Html::beginForm(['site/logout'], 'post', ['class' => 'navbar-form'])
                . Html::submitButton(
                    'Выход (' . Yii::$app->user->identity->username . ')',
                    ['class' => 'btn btn-link']
                )
                . Html::endForm()
                . '</li>'
            )
        ],
    ]);
    ?>
    <?=Html::beginForm(['site/search'], 'get', ['class' => 'navbar-form','role'=>'search']);?>
        <div class="form-group">
        <?=AutoComplete::widget([
            'name'=>'search',
            'options' => ['class'=>'form-control','placeholder' => 'Поиск','id'=>'search_input'],
            'clientOptions' => [
                'source' =>Url::to(['site/ajax-search']),
                'autoFill'=>true,
                'minLength'=>'0',
                '_renderItem'=>new JsExpression(
                    'function( ul, item ) {
                        return $( "<li></li>" )
                            .data( "item.autocomplete", item )
                            .append( "<a href=\""+item.url+"\">" + item.name + "</a>")
                            .appendTo( ul );
                    }'),
            ],
        ]);?>
        <?php
        $this->registerJs('$("#search_input").autocomplete( "instance" )._renderItem = function( ul, item ) {
                        return $( "<li></li>" )
                            .data( "item.autocomplete", item )
                            .append( "<a href=\""+item.url+"\">" + item.name + "</a>")
                            .appendTo( ul );
                    }');
        ?>
        </div>
    <?=Html::endForm()?>
    <?php NavBar::end(); ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
