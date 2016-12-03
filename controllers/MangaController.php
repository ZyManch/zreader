<?php

namespace app\controllers;

use app\form\FilterForm;
use app\models\Session;
use Yii;
use app\models\ar;
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

    public function actionExclude($manga, $redirect) {
        $model = $this->findModelByUrl($manga);
        /** @var Session $session */
        $session = Yii::$app->user->getSession();
        $session->changeMangaStatus($model,ar\SessionHasManga\Model::STATUS_HIDE);
        $this->redirect($redirect);
    }

    /**
     * Displays a single Manga model.
     * @param string $manga
     * @return mixed
     */
    public function actionView($manga, $season_id = null)
    {
        $model = $this->findModelByUrl($manga);
        $model->incrementViews();
        $season = $this->findSeason($model, $season_id);
        return $this->render('view', [
            'model' => $model,
            'season' => $season
        ]);
    }

    protected function findSeason(ar\Manga\Model $manga, $seasonId) {
        if ($seasonId) {
            $model = ar\Season\Model::find()->
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
