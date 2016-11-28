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
use yii\helpers\FileHelper;
use yii\helpers\Url;

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
    public $silent = false;

    public function options()
    {
        return ['task','count','task_id','silent'];
    }

    public function optionAliases()
    {
        return ['c' => 'count','t'=> 'task','id' => 'task_id','s'=>'silent'];
    }

    public function actionIndex() {
        set_time_limit(0);
        ini_set('memory_limit','1024M');
        if ($this->_isAlreadyExecuted()) {
            $this->_output('Another script is already in process');
            return;
        }
        try {
            $this->_lockExecution();
            for ($i = 1; $i <= $this->count; $i++) {
                if (!$this->_processTask()) {
                    break;
                }
            }
        } finally {
            $this->_unlockExecution();
        }
    }

    protected function _processTask() {
        $query = ar\Task::find()->orderBy('task_id');
        if ($this->task_id) {
            $query->
                where('task_id='.intval($this->task_id));
        } else {
            $query->
                where('status="'.ar\Task::STATUS_WAIT.'"');
            if ($this->task) {
                $query->andWhere('task="'.$this->task.'"');
            }
        }
        $task = $query->one();
        /** @var $task ar\Task */
        if ($task) {
            $this->_output('Start task '.$task->task_id.':');

            try {
                $task->process();
                $this->_output('success');
            }   catch (\Exception $e) {
                $this->_output($e->getFile().':'.$e->getLine().' - '.$e->getMessage());
            }
        } else {
            $this->_output('No tasks to process');
            return false;
        }
        $this->_output("\n");
        return true;
    }

    protected function _isAlreadyExecuted() {
        return file_exists($this->_getLockFileName());

    }

    protected function _lockExecution() {
        file_put_contents($this->_getLockFileName(),date('Y-m-d H:i:s'));
    }

    protected function _unlockExecution() {
        unlink($this->_getLockFileName());
    }

    protected function _getLockFileName() {
        $path = \Yii::$app->getRuntimePath().'/task/';
        if (!is_dir($path)) {
            FileHelper::createDirectory($path, 0777, true);
        }
        return $path.'lock.txt';
    }

    protected function _output($text) {
        if (!$this->silent) {
            $this->stdout($text);
        }
    }

}
