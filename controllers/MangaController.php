<?php

namespace app\controllers;

use app\form\FilterForm;
use app\models\Session;
use Yii;
use app\models\ar;
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


        $form = new FilterForm();
        $form->attributes = Yii::$app->request->getQueryParams();
        return $this->render('index',array(
            'model' => $form
        ));
    }

    public function actionBest()
    {
        $query = ar\Manga\Model::find()->
            excludeHidden()->
            best();
        return $this->render('best',array(
            'dataProvider' => new ActiveDataProvider([
                'query' => $query
            ])
        ));
    }

    public function actionFavorites()
    {
        $query = ar\Manga\Model::find()->
            favorite();
        return $this->render('favorites',array(
            'dataProvider' => new ActiveDataProvider([
                'query' => $query
            ])
        ));
    }

    /**
     * Displays a single Manga model.
     * @param string $manga
     * @return mixed
     */
    public function actionView($manga)
    {
        $model = $this->findModelByUrl($manga);
        $model->incrementViews();
        return $this->render('view', [
            'model' => $model
        ]);
    }


    /**
     * @param $url
     * @return ar\Manga\Model
     * @throws NotFoundHttpException
     */
    protected function findModelByUrl($url)
    {
        $model = ar\Manga\Model::find()->where(['url'=>$url])->one();
        if (!$model) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        return $model;
    }
}
