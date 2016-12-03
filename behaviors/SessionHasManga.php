<?php
/**
 * Created by PhpStorm.
 * User: ZyManch
 * Date: 18.11.2016
 * Time: 15:08
 */
namespace app\behaviors;

use app\models\Session;
use yii\base\Behavior;
use yii\base\ModelEvent;
use yii\db\ActiveRecord;

class SessionHasManga extends Behavior {

    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',
        ];
    }

    public function beforeValidate(ModelEvent $event)
    {
        /** @var \app\models\ar\SessionHasManga\Model $sessionHasManga */
        $sessionHasManga = $event->sender;
        /** @var Session $session */
        $session = \Yii::$app->user->getSession();
        $sessionHasManga->session_id = $session->getSessionId();
        return true;
    }


}