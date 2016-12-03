<?php

namespace app\controllers;

use app\models\ar;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * SeasonController implements the CRUD actions for Season model.
 */
class ReadController extends Controller
{

    public $layout = 'read';



    /**
     * Displays a single Season model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id, $manga)
    {
        $chapter = $this->findModel($id, $manga);
        $chapter->season->manga->incrementReads();
        return $this->render('view', [
            'model' => $chapter,
        ]);
    }

    /**
     * Finds the Season model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return ar\Chapter\Model the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $manga)
    {
        /** @var $model ar\Chapter\Model */
        if (($model = ar\Chapter\Model::findOne($id)) !== null) {
            if ($model->season->manga->url != $manga) {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
