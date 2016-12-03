<?php

namespace app\controllers;

use Yii;
use app\models\ar;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * SeasonController implements the CRUD actions for Season model.
 */
class SeasonController extends Controller
{




    /**
     * Finds the Season model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return ar\Season\Model the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $manga)
    {
        /** @var $model ar\Season\Model */
        if (($model = ar\Season\Model::findOne($id)) !== null) {
            if ($model->manga->url != $manga) {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
