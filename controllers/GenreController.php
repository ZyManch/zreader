<?php

namespace app\controllers;

use Yii;
use app\models\ar;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * GenreController implements the CRUD actions for Genre model.
 */
class GenreController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Genre models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => ar\Genre\Model::find(),
            'pagination'=>false
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Genre model.
     * @param string $id
     * @return mixed
     */
    public function actionView($genre)
    {
        return $this->render('view', [
            'model' => $this->findModel($genre),
        ]);
    }

    /**
     * @param string $genreUrl
     * @return ar\Genre\Model the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($genreUrl)
    {
        $model = ar\Genre\Model::find()->where(['url' => $genreUrl])->one();
        if ($model) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
