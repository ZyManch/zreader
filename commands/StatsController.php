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
        $this->_mangaStat();
        $this->_sessionManga();

    }

    protected function _mangaStat() {
        $affectedRows = \Yii::$app->db->createCommand('
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
        $this->stdout("Updated $affectedRows mangas\n");
    }

    protected function _sessionManga() {
        $affectedRows = \Yii::$app->db->createCommand('
            update session_has_manga s
            join (
                select c.manga_id,max(c.number) as number
                from chapter c
                where c.created > adddate(now(),interval -1 day)
                group by c.manga_id
            ) t
            set s.is_read_finished = if(s.last_chapter_number < t.number,"no","yes")
        ')->execute();
        $this->stdout("Updated $affectedRows users\n");
    }

}
