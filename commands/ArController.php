<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use yii\gii\CodeFile;
use yii\models;
use app\models\Generator;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class ArController extends Controller
{

    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionIndex() {
        $files = [];
        $tables = $this->_getTables();
        exec('rm -fR '.\Yii::getAlias('@app/models/ar/_origin'));
        $modelNs = 'app\models\ar';
        foreach ($tables as $table => $class) {
            $generator = new Generator();
            $generator->tableName = $table;
            $generator->modelClass = 'C'.$class;
            $generator->queryClass = 'C'.$class.'Query';
            $generator->modelNs = $modelNs.'\\'.$class;
            $generator->templates['default'] = \Yii::getAlias('@app/commands/default');
            foreach ($tables as $relationTableName => $relationClass) {
                $generator->classNames[$relationTableName] = 'ar\\'.$relationClass.'\\Model';
            }
            $files = array_merge($files, $generator->generate());
            $originPath = \Yii::getAlias('@app/models/ar').'/'.$class.'/Model.php';
            if (!file_exists($originPath)) {
                $file = new CodeFile($originPath, $this->_getOriginFile($generator->modelNs,'Model','C'.$class));
                $files[] = $file;
            }
            $originQueryPath = \Yii::getAlias('@app/models/ar').'/'.$class.'/Query.php';
            if (!file_exists($originQueryPath)) {
                $file = new CodeFile($originQueryPath, $this->_getOriginFile($generator->modelNs,'Query','C'.$class.'Query'));
                $files[] = $file;
            }
        }
        $this->module = new \stdClass(['newDirMode'=>'0777','newFileMode'=>'0777']);
        /** @var CodeFile $file */
        foreach ($files as $file) {
            $success = $file->save();
            if ($success !== true) {
                $this->stdout(sprintf("File %s: %s\n",$file->path, $success));
            } else {
                $this->stdout(sprintf("File %s: ok\n",$file->path));
            }
        }
        exec(sprintf(
            'git add %s',
            \Yii::getAlias('@app/models/ar')
        ));
    }

    protected function _getTables() {
        $tables = \Yii::$app->db->createCommand('show tables')->queryColumn();
        $result = [];
        foreach ($tables as $table) {
            if (!in_array($table,['migration'])) {
                $result[$table] = implode('', array_map('ucfirst', explode('_', $table)));
            }
        }
        return $result;
    }

    protected function _getOriginFile($namespace, $class, $extends) {
        return '<?'."php\n\nnamespace $namespace;\n\nuse app\\models\\ar;\n\n".
            "class $class extends _origin\\$extends {\n\n}";
    }

}
