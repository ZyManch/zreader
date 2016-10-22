<?php

namespace app\controllers;

use Yii;
use app\models\ar\Manga;
use app\models\ar\Season;
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
    public function actionView($manga, $season_id = null)
    {
        $model = $this->findModelByUrl($manga);
        $season = $this->findSeason($model, $season_id);
        return $this->render('view', [
            'model' => $model,
            'season' => $season
        ]);
    }

    protected function findSeason(Manga $manga, $seasonId) {
        if ($seasonId) {
            $model = Season::find()->
                where(['manga_id' => $manga->manga_id, 'season_id' => $seasonId])->
                one();
        } else {
            $model = $manga->getSeasons()->orderBy('position')->one();
        }
        if (!$model) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        return $model;
    }

    protected function findModelByUrl($url)
    {
        $model = Manga::find()->where(['url'=>$url])->one();
        if (!$model) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        return $model;
    }
}
