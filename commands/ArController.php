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

    protected $_tables = array(
        'user'=>'User',
        'manga'=>'Manga',
        'chapter'=>'Chapter',
        'image'=>'Image',
        'season'=>'Season',
    );
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionIndex() {
        $files = [];

        foreach ($this->_tables as $table => $class) {
            $generator = new Generator();
            $generator->tableName = $table;
            $generator->ns = 'app\models\ar\origin';
            $generator->modelClass = 'C'.$class;
            $generator->generateQuery = true;
            $generator->queryNs = 'app\models\ar\origin';
            $generator->queryClass = 'C'.$class.'Query';
            $generator->templates['default'] = \Yii::getAlias('@app/commands/default');
            foreach ($this->_tables as $relationTableName => $relationClass) {
                $generator->classNames[$relationTableName] = 'ar\\'.$relationClass;
            }
            $files = array_merge($files, $generator->generate());
        }
        $this->module = new \stdClass(['newDirMode'=>'0777','newFileMode'=>'0777']);
        /** @var CodeFile $file */
        foreach ($files as $file) {
            $this->stdout(sprintf("File %s: ok\n",$file->path));
            $file->save();
        }
    }
}
