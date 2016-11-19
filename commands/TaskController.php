<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use app\models;
use app\models\ar;
/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class TaskController extends Controller
{


    public $task;
    public $task_id;
    public $count = 1;

    public function options()
    {
        return ['task','count','task_id'];
    }

    public function optionAliases()
    {
        return ['c' => 'count','t'=> 'task','id' => 'task_id'];
    }

    public function actionIndex() {
        ini_set('memory_limit','512M');
        for ($i=1;$i<=$this->count;$i++) {
            if ($this->task_id) {
                $task = ar\Task::find()->
                    where('task_id='.intval($this->task_id))->
                    one();
            } else {
                $task = ar\Task::find()->
                    where('status="'.ar\Task::STATUS_WAIT.'"')->
                    orderBy('task_id')->
                    one();
            }
            /** @var $task ar\Task */
            if ($task) {
                $this->stdout('Start task '.$task->task_id.':');
                try {
                    $task->process();
                    $this->stdout('success');
                }   catch (\Exception $e) {
                    $this->stdout($e->getMessage());
                }
            } else {
                $this->stdout('No tasks to process');
                break;
            }
            $this->stdout("\n");
        }
    }


}
