<?php

namespace app\controllers;

use app\form\FilterForm;
use app\models\ar;
use yii\helpers\Url;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $continue = ar\Manga\Model::find()->
            notFinished()->
            excludeHidden()->
            orderByFavorites()->
            orderByDeferred()->
            orderByLastChapter()->
            limit(ar\Manga\Query::BEST_MANGA_COUNT)->
            all();
        $favorites = ar\Manga\Model::find()->
            favorite()->
            orderByLastChapter()->
            limit(ar\Manga\Query::BEST_MANGA_COUNT)->
            all();
        $lastManga = ar\Manga\Model::find()->
            excludeHidden()->
            orderByDeferred()->
            orderByLastChapter()->
            limit(ar\Manga\Query::BEST_MANGA_COUNT)->
            all();
        $bestMangas = ar\Manga\Model::find()->
            excludeHidden()->
            orderByBest()->
            limit(ar\Manga\Query::BEST_MANGA_COUNT)->
            all();
        return $this->render('index',array(
            'lastManga' => $lastManga,
            'bestMangas' => $bestMangas,
            'favorites' => $favorites,
            'continue' => $continue
        ));
    }

    public function actionAjaxSearch($term) {
        if (strlen($term) > 100) {
            $term = substr($term,0,100);
        }
        $mangas = ar\Manga\Model::find()->
            search($term)->
            limit(ar\Manga\Query::SEARCH_MANGA_COUNT)->
            all();
        $result = [];
        foreach ($mangas as $manga) {
            $result[] = [
                'label' => $manga->title,
                'name'=>$manga->title,
                'url' => Url::to($manga->getUrl())
            ];
        }
        header('Content-type: application/json');
        return json_encode($result);
    }


    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }


}
