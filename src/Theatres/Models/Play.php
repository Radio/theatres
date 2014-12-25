<?php

namespace Theatres\Models;

use RedBean_Facade as R;
use Theatres\Exceptions\MoreThanOneBeanFound;
use Theatres\Helpers\Models;

/**
 * Play
 *
 * @property int    $id          ID
 * @property string $theatre     FK, Театр
 * @property string $scene       FK, Сцена
 * @property string $key         Ключ
 * @property string $title       Название
 * @property string $link        Ссылка на страницу на сайте театра
 * @property string $director    Постановщик
 * @property string $author      Автор пьесы
 * @property string $genre       Жанр
 * @property string $duration    Длительность
 * @property string $description Описание, сюжет
 * @property string $picture     Картинка спектакля
 *
 * @package Theatres\Models
 */
class Play extends \RedBean_SimpleModel
{
    protected $previousKey;

    /**
     * Load play by tag (which is a variant of play title).
     *
     * @param $tag
     * @throws \Theatres\Exceptions\MoreThanOneBeanFound
     */
    public function loadByTag($tag)
    {
        $plays = R::tagged('play', array($tag));
        if ($plays) {
            if (count($plays) == 1) {
                $this->bean->importFrom(current($plays));
            } else {
                throw new MoreThanOneBeanFound('More than one bean found for tag "' . $tag . '"');
            }
        }
    }

    public function update()
    {
        if (!$this->id) {
            if (!$this->key && $this->title) {
                $this->key = Models::generateKey($this->title);
            }
        }
    }

    public function after_update()
    {
        $originalData = $this->unbox()->getMeta('sys.orig');
        if (isset($originalData['key'])) {
            $previousKey = ['key'];
            if ($previousKey !== $this->key) {
                R::exec('update `show` set `play` = ? where `play` = ?', array($this->key, $previousKey));
            }
        }
    }
}