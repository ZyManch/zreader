<?php

namespace app\controllers;

use Yii;
use app\models\ar\Season;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * SeasonController implements the CRUD actions for Season model.
 */
class SeasonController extends Controller
{


    /**
     * Displays a single Season model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id, $manga)
    {
        return $this->render('view', [
            'model' => $this->findModel($id, $manga),
        ]);
    }

    /**
     * Finds the Season model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Season the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $manga)
    {
        /** @var $model Season */
        if (($model = Season::findOne($id)) !== null) {
            if ($model->manga->url != $manga) {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
