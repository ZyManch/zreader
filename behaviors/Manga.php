<?php
/**
 * Created by PhpStorm.
 * User: ZyManch
 * Date: 18.11.2016
 * Time: 15:08
 */
namespace app\behaviors;

use yii\base\Behavior;
use yii\base\ModelEvent;
use yii\db\ActiveRecord;

class Manga extends Behavior {

    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',
        ];
    }

    public function beforeValidate(ModelEvent $event)
    {
        /** @var \app\models\ar\Manga $manga */
        $manga = $event->sender;
        if (!$manga->url) {
            $manga->url = $this->_titleToUrl($manga->title);
        }
        return true;
    }

    protected function _titleToUrl($title) {
        $converter = array(
            'а' => 'a',   'б' => 'b',   'в' => 'v',
            'г' => 'g',   'д' => 'd',   'е' => 'e',
            'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
            'и' => 'i',   'й' => 'y',   'к' => 'k',
            'л' => 'l',   'м' => 'm',   'н' => 'n',
            'о' => 'o',   'п' => 'p',   'р' => 'r',
            'с' => 's',   'т' => 't',   'у' => 'u',
            'ф' => 'f',   'х' => 'h',   'ц' => 'c',
            'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
            'ь' => '',  'ы' => 'y',   'ъ' => '',
            'э' => 'e',   'ю' => 'yu',  'я' => 'ya',

            'А' => 'A',   'Б' => 'B',   'В' => 'V',
            'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
            'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
            'И' => 'I',   'Й' => 'Y',   'К' => 'K',
            'Л' => 'L',   'М' => 'M',   'Н' => 'N',
            'О' => 'O',   'П' => 'P',   'Р' => 'R',
            'С' => 'S',   'Т' => 'T',   'У' => 'U',
            'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
            'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
            'Ь' => '',  'Ы' => 'Y',   'Ъ' => '',
            'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
        );

        $engChars = 'qwertyuiopasdfghjklzxcvbnm';
        $engChars.=strtoupper($engChars);
        $engChars = str_split($engChars);
        $result = [];
        $oldCharIsSkeep = true;
        for ($i=0;$i<strlen($title);$i++) {
            $char = mb_substr($title,$i,1);
            if (is_numeric($char) || in_array($char, $engChars)) {
                $result[] = $char;
                $oldCharIsSkeep = false;
            } else if (isset($converter[$char])) {
                $result[] = $converter[$char];
                $oldCharIsSkeep = false;
            } else {
                if (!$oldCharIsSkeep) {
                    $result[] = '-';
                }
                $oldCharIsSkeep = true;
            }
        }
        return rtrim(implode('',$result),'-');
    }

}