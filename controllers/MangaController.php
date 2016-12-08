<?php

namespace app\controllers;

use app\form\FilterForm;
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
            orderByBest();
        return $this->render('best',array(
            'dataProvider' => new ActiveDataProvider([
                'query' => $query
            ])
        ));
    }

    public function actionFavorites()
    {
        $query = ar\Manga\Model::find()->
            favorite()->
            orderByLastChapter();
        return $this->render('favorites',array(
            'dataProvider' => new ActiveDataProvider([
                'query' => $query
            ])
        ));
    }

    public function actionHidden()
    {
        $query = ar\Manga\Model::find()->
            hidden()->
            orderByLastChapter();
        return $this->render('hidden',array(
            'dataProvider' => new ActiveDataProvider([
                'query' => $query
            ])
        ));
    }

    public function actionDeferred()
    {
        $query = ar\Manga\Model::find()->
            deferred()->
            orderByDeferred()->
            orderByLastChapter();
        return $this->render('deferred',array(
            'dataProvider' => new ActiveDataProvider([
                'query' => $query
            ])
        ));
    }

    public function actionContinue()
    {
        $query = ar\Manga\Model::find()->
            notFinished()->
            excludeHidden()->
            orderByFavorites()->
            orderByDeferred()->
            orderByLastChapter();
        return $this->render('continue',array(
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



    public function actionSearch($search) {
        if (strlen($search) > 100) {
            $search = substr($search,0,100);
        }
        $mangas = ar\Manga\Model::find()->
            search($search)->
            all();
        return $this->render('search',array(
            'search' => $search,
            'mangas' => $mangas,
        ));
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
