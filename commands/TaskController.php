<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */


//yii task --trace=1 -f --task_id=124286


namespace app\commands;

use yii\console\Controller;
use app\models\ar;
use yii\helpers\FileHelper;

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
    public $force = false;
    public $trace = false;

    public function init() {
        parent::init();
        set_time_limit(0);
        ini_set('memory_limit','1024M');
    }

    public function options($actionID)
    {
        return ['task','count','task_id','silent','force','trace'];
    }

    public function optionAliases()
    {
        return [
            'c' => 'count',
            't' => 'task',
            'id'=> 'task_id',
            's' => 'silent',
            'f' => 'force'
        ];
    }

    public function actionIndex() {
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
        $query = ar\Task\Model::find()->orderBy('task_id');
        if ($this->task_id) {
            $query->
                where('task_id='.intval($this->task_id));
        } else {
            $query->
                where('status="'.ar\Task\Model::STATUS_WAIT.'"');
            if ($this->task) {
                $query->andWhere('task="'.$this->task.'"');
            }
        }
        $task = $query->one();
        /** @var $task ar\Task\Model */
        if ($task) {
            $this->_output('Start task '.$task->task_id);
            if ($this->trace) {
                $this->_output("\n");
            } else {
                $this->_output(':');
            }
            try {
                $start = microtime(true);
                $task->process($this->trace ? $this : null);
                $duration = microtime(true) - $start;
                if ($this->trace) {
                    $this->_output('Task finished success');
                } else {
                    $this->_output('success');
                }
                $this->_output(' ['.round($duration,1).' sec]');
            }   catch (\Exception $e) {
                print $e->getTraceAsString();
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
        if ($this->force) {
            return false;
        }
        if (!file_exists($this->_getLockFileName())) {
            return false;
        }
        $content = file_get_contents($this->_getLockFileName());
        $parts = explode(' ',$content,2);
        @exec(sprintf('ps %d',$parts[0]), $output, $result);
        return sizeof($output) >= 2;

    }

    protected function _lockExecution() {
        if (!$this->force) {
            file_put_contents(
                $this->_getLockFileName(),
                getmypid().' '.date('Y-m-d H:i:s')
            );
        }
    }

    protected function _unlockExecution() {
        if (!$this->force) {
            unlink($this->_getLockFileName());
        }
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
