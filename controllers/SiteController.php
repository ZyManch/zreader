<?php

namespace app\controllers;

use app\form\FilterForm;
use app\models\ar\Chapter;
use app\models\ar\Genre;
use app\models\ar\Manga;
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
        $lastChapters = Chapter::getGroupedLastChapters();
        $bestMangas = Manga::getBestMangas();
        $genres = Genre::getAll();
        return $this->render('index',array(
            'lastChapters' => $lastChapters,
            'bestMangas' => $bestMangas,
            'genres' => $genres

        ));
    }

    public function actionAjaxSearch($term) {
        if (strlen($term) > 100) {
            $term = substr($term,0,100);
        }
        $mangas = Manga::getMangaByWord($term);
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


    public function actionSearch($search) {
        if (strlen($search) > 100) {
            $search = substr($search,0,100);
        }
        $mangas = Manga::getMangaByWord($search);
        return $this->render('search',array(
            'search' => $search,
            'mangas' => $mangas,
        ));
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
