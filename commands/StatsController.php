<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use app\models\ar;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class StatsController extends Controller
{

    public function actionIndex() {
        \Yii::$app->db->createCommand('
            update manga m
            left join (
              select c.manga_id, 
                        count(*) as chapters, 
                        max(c.created) as changed
              from chapter c
              group by c.manga_id
            ) stat using(manga_id)
            set m.chapters=ifnull(stat.chapters,0),
                 m.changed =ifnull(stat.changed,"0000-00-00 00:00:00")
        ')->execute();

    }

}
