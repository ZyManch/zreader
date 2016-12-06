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

    public function actionFavorite($manga, $redirect) {
        $model = $this->findModelByUrl($manga);
        /** @var Session $session */
        $session = Yii::$app->user->getSession();
        $session->changeMangaStatus($model,ar\SessionHasManga\Model::STATUS_FAVORITE);
        $this->redirect($redirect);
    }

    public function actionShow($manga, $redirect) {
        $model = $this->findModelByUrl($manga);
        /** @var Session $session */
        $session = Yii::$app->user->getSession();
        $session->changeMangaStatus($model,ar\SessionHasManga\Model::STATUS_STARTED);
        $this->redirect($redirect);
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
