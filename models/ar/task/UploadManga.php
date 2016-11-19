<?php

namespace app\models\ar\task;

use app\models\ar\Author;
use app\models\ar\Chapter;
use app\models\ar\Genre;
use app\models\ar\Manga;
use app\models\ar\MangaHasAuthor;
use app\models\ar\MangaHasGenre;
use app\models\ar\Season;
use app\models\ar\Task;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;


class UploadManga extends Task
{


    protected $_genreCache;
    protected $_authorCache;

    public function init() {
        parent::init();
        $this->task = self::TASK_UPLOAD_MANGA;
    }

    protected function _process() {
        $page = $this->_requestPage();
        $title = $this->_extractTitle($page);
        $originTitle = $this->_extractOriginTitle($page);
        $englishTitle = $this->_extractEnglishTitle($page);
        $manga = $this->_getManga($originTitle, $englishTitle, $title);
        if (!$manga && !$title) {
            throw new \Exception('Can`t find any manga on page'.$this->filename);
        }
        if (!$manga) {
            $manga = $this->_createManga($originTitle, $englishTitle, $title, $page);
        }
        $season = $manga->getSeasonByTitle('Season 1');

        $this->_assignChapters($season, $page);
    }

    protected function _assignChapters(Season $season, $html) {
        $newChapters = $this->_getChapters($html);
        $oldChapters = ArrayHelper::map($season->getChapters()->all(),'number','chapter_id');
        $domain = parse_url($this->filename, PHP_URL_HOST);
        foreach ($newChapters as $number => $chapterDetail) {
            if (!isset($oldChapters[$number])) {
                $chapter = new Chapter();
                $chapter->season_id = $season->season_id;
                $chapter->number = $number;
                $chapter->title = (trim($chapterDetail['title'])? trim($chapterDetail['title']) : null);
                if (!$chapter->save()) {
                    throw new \Exception('Can`t create chapter: '.implode(',',$chapter->getFirstErrors()));
                }
                $task = new UploadChapter();
                $task->manga_id = $season->manga_id;
                $task->season_id = $season->season_id;
                $task->chapter_id = $chapter->chapter_id;
                $task->filename = 'http://'.$domain.$chapterDetail['url'];
                if (!$task->save()) {
                    throw new \Exception('Can`t create chapter: '.implode(',',$chapter->getFirstErrors()));
                }
            }
        }

    }

    protected function _extractTitle($page) {
        if (!preg_match('#<span class=\'name\'>([^<]+)<\/span>#',$page, $matches)) {
            return '';
        }
        return trim($matches[1]);
    }

    protected function _extractOriginTitle($page) {
        if (!preg_match('#<span class=\'original-name\' title="[^"]+">([^<]+)<\/span>#',$page, $matches)) {
            return null;
        }
        return trim($matches[1]);
    }

    protected function _extractEnglishTitle($page) {
        if (!preg_match('#<span class=\'eng-name\' title="[^"]+">([^<]+)<\/span>#',$page, $matches)) {
            return null;
        }
        return trim($matches[1]);
    }

    protected function _extractIsFinished($page) {
        if (!preg_match('#<p><b>Перевод:<\/b>([^<]+)<\/p>#',$page, $matches)) {
            return null;
        }
        return trim($matches[1]) == 'продолжается' ? Manga::IS_FINISHED_NO : Manga::IS_FINISHED_YES;
    }

    protected function _extractYear($page) {
        if (!preg_match('/<a href="\/list\/year\/([^"]+)" class="element-link">/',$page, $matches)) {
            return null;
        }
        return trim($matches[1]);
    }

    protected function _extractDescription($page) {
        if (!preg_match('/<div class="manga-description" itemprop="description">(.*)<div class="clearfix"><\/div>/su',$page, $matches)) {
            return null;
        }
        return nl2br(trim(strip_tags(str_replace('<div',"\n<div",$matches[1]))));
    }

    protected function _assignGenres(Manga $manga, $page) {
        $titleGenres = $this->_getGenres();
        $genresIds = array_flip($titleGenres);
        if (preg_match_all('#<a href="\/list\/genre\/[^"]+" class="element-link">([^<]+)<\/a>#',$page, $matches)) {
            $newGenres = $matches[1];
        } else {
            $newGenres = [];
        }
        /** @var MangaHasGenre[] $oldGenres */
        $oldGenres = $manga->getMangaHasGenres()->all();
        foreach ($oldGenres as $oldGenre) {
            $title = $genresIds[$oldGenre->genre_id];
            if (in_array($title, $newGenres)) {
                unset($newGenres[array_search($title, $newGenres)]);
            } else {
                $oldGenre->delete();
            }
        }
        foreach ($newGenres as $title) {
            if (isset($titleGenres[$title])) {
                $genreId = $titleGenres[$title];
            } else {
                $genre = new Genre();
                $genre->title = $title;
                $genre->save(false);
                $genreId = $genre->genre_id;
            }
            $genre = new MangaHasGenre();
            $genre->manga_id = $manga->manga_id;
            $genre->genre_id = $genreId;
            $genre->save();
        }
    }

    protected function _getChapters($html) {
        if (!preg_match('#<span class="read-first"><a href="([^"]+)#',$html, $matches)) {
            return [];
        }
        $domain = parse_url($this->filename, PHP_URL_HOST);
        $firstChapter = $this->_requestPage('http://'.$domain.$matches[1]);
        if (!preg_match('#<select id="chapterSelectorSelect"(.*)<\/select>#su',$firstChapter, $matches)) {
            return [];
        }
        if (!preg_match_all('#<option value="([^"]+)"[^>]+>([^<]+)<\/option>#',$matches[1], $matches)) {
            return [];
        }
        $result = [];
        foreach ($matches[1] as $index => $url) {
            $title = explode(' - ',$matches[2][$index],2);
            if (sizeof($title)==2) {
                $title = $title[1];
            } else {
                $title = $title[0];
            }
            $parts = explode(' ',$title,2);
            $result[$parts[0]] = [
                'title' => $parts[1],
                'url' => $url
            ];
        }
        ksort($result);
        return $result;
    }


    protected function _assignAvatar(Manga $manga, $html) {
        if (!preg_match('#"([^"]+)" data-thumb=#',$html, $matches)) {
            return null;
        }
        $fileName = $matches[1];
        $storingFileName = Url::to('@app').'/public/manga/avatar/'.$manga->url.'.jpg';
        $image = $this->_requestPage($fileName);
        file_put_contents($storingFileName, $image);
        $gd = imagecreatefromjpeg($storingFileName);
        $oldW = imagesx($gd);
        $oldH = imagesy($gd);
        $newW = 200;
        $newH = round($oldH*$newW/$oldW);
        $newGd = imagecreatetruecolor($newW, $newH);
        imagecopyresized($newGd, $gd,0,0,0,0,$newW, $newH,$oldW, $oldH);
        imagejpeg($newGd,$storingFileName,85);
    }

    protected function _getManga($originalTitle, $englishTitle, $title) {
        if (!$title) {
            return null;
        }
        $manga = null;
        if ($originalTitle) {
            $manga = Manga::find()->where('original_title=:title',array(':title' => $originalTitle))->one();
        } else if ($englishTitle) {
            $manga = Manga::find()->where('english_title=:title',array(':title' => $originalTitle))->one();
        } else if ($title) {
            $manga = Manga::find()->where('title=:title',array(':title' => $originalTitle))->one();
        }
        return $manga;
    }

    protected function _createManga($originalTitle, $englishTitle, $title, $html) {
        $manga = new Manga();
        $manga->title = $title;
        $manga->english_title = ($englishTitle ? $englishTitle : null);
        $manga->original_title = ($originalTitle ? $originalTitle : null);
        $manga->is_finished = $this->_extractIsFinished($html);
        $manga->description = $this->_extractDescription($html);
        $manga->created = $this->_extractYear($html);
        if (!$manga->save()) {
            throw new \Exception('Error create manga: '.implode(',',$manga->getFirstErrors()));
        }
        $this->_assignGenres($manga, $html);
        $this->_assignAuthors($manga, $html);
        $this->_assignAvatar($manga, $html);
        return $manga;
    }

    protected function _assignAuthors(Manga $manga, $html) {
        $titleAuthors = $this->_getGenres();
        $authorsIds = array_flip($titleAuthors);
        $newAuthors = [];
        if (preg_match_all('#<span class="elem_author ">(.*)<\/span>#',$html, $matches)) {
             foreach ($matches[1] as $author) {
                 $newAuthors[] = trim(strip_tags($author));
             }
        }
        /** @var MangaHasAuthor[] $oldAuthors */
        $oldAuthors = $manga->getMangaHasAuthors()->all();
        foreach ($oldAuthors as $oldAuthor) {
            $title = $authorsIds[$oldAuthor->author_id];
            if (in_array($title, $newAuthors)) {
                unset($newAuthors[array_search($title, $newAuthors)]);
            } else {
                $oldAuthor->delete();
            }
        }
        foreach ($newAuthors as $title) {
            if (isset($titleAuthors[$title])) {
                $authorId = $titleAuthors[$title];
            } else {
                $author = new Author();
                $author->name = $title;
                $author->save(false);
                $authorId = $author->author_id;
            }
            $author = new MangaHasAuthor();
            $author->manga_id = $manga->manga_id;
            $author->author_id = $authorId;
            $author->save();
        }
    }

    protected function _getGenres() {
        if (is_null($this->_genreCache)) {
            $this->_genreCache = ArrayHelper::map(Genre::find()->all(),'title','genre_id');
        }
        return $this->_genreCache;
    }

    protected function _getAuthors() {
        if (is_null($this->_authorCache)) {
            $this->_authorCache = ArrayHelper::map(Author::find()->all(),'name','author_id');
        }
        return $this->_authorCache;
    }
}
