<?php

namespace app\controllers;

use app\models\Session;
use Yii;
use app\models\ar;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * MangaController implements the CRUD actions for Manga model.
 */
class SessionController extends Controller
{


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
