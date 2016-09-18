<?php

namespace app\controllers;

use Yii;
use app\models\ar\Manga;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * MangaController implements the CRUD actions for Manga model.
 */
class MangaController extends Controller
{

    /**
     * Lists all Manga models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Manga::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Manga model.
     * @param string $manga
     * @return mixed
     */
    public function actionView($manga)
    {
        $model = $this->findModelByUrl($manga);
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    protected function findModelByUrl($url)
    {
        if (($model = Manga::find()->where(['url'=>$url])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
