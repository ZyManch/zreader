<?php

namespace app\controllers;

use app\models\session\Settings;
use Yii;
use app\models\ar;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * MangaController implements the CRUD actions for Manga model.
 */
class SessionController extends Controller
{


    public function actionMarkHidden($manga, $redirect) {
        $model = $this->findModelByUrl($manga);
        /** @var Settings $session */
        $session = Yii::$app->user->getSession();
        $session->changeMangaStatus($model,ar\SessionHasManga\Model::STATUS_HIDE);
        $this->redirect($redirect);
    }

    public function actionMarkFavorite($manga, $redirect) {
        $model = $this->findModelByUrl($manga);
        /** @var Settings $session */
        $session = Yii::$app->user->getSession();
        $session->changeMangaStatus($model,ar\SessionHasManga\Model::STATUS_FAVORITE);
        $this->redirect($redirect);
    }

    public function actionMarkDefault($manga, $redirect) {
        $model = $this->findModelByUrl($manga);
        /** @var Settings $session */
        $session = Yii::$app->user->getSession();
        $session->changeMangaStatus($model,ar\SessionHasManga\Model::STATUS_UNKNOWN);
        $this->redirect($redirect);
    }

    public function actionMarkStarted($manga, $redirect) {
        $model = $this->findModelByUrl($manga);
        /** @var Settings $session */
        $session = Yii::$app->user->getSession();
        $session->changeMangaStatus($model,ar\SessionHasManga\Model::STATUS_STARTED);
        $this->redirect($redirect);
    }

    public function actionMarkDeferred($manga, $redirect) {
        $model = $this->findModelByUrl($manga);
        /** @var Settings $session */
        $session = Yii::$app->user->getSession();
        $session->changeMangaStatus($model,ar\SessionHasManga\Model::STATUS_DEFERRED);
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
