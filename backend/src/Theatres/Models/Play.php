<?php

namespace Theatres\Models;

use RedBean_Facade as R;
use Theatres\Core\Model_Bean;
use Theatres\Exceptions\MoreThanOneBeanFound;
use Theatres\Helpers;

/**
 * Play
 *
 * @property int    $id          ID
 * @property string $theatre_id  FK, ID Театра
 * @property string $theatre     Театр
 * @property string $scene_id    FK, ID Сцены
 * @property string $scene       Сцена
 * @property string $key         Ключ
 * @property string $title       Название
 * @property string $link        Ссылка на страницу на сайте театра
 * @property string $director    Постановщик
 * @property string $author      Автор пьесы
 * @property string $genre       Жанр
 * @property string $duration    Длительность
 * @property string $description Описание, сюжет
 * @property string $image       Картинка спектакля
 *
 * @package Theatres\Models
 */
class Play extends Model_Bean
{
    public static $allowedFields = [
        'theatre_id', 'theatre', 'scene_id', 'scene',
        'title', 'key', 'link',  'price', 'image',
        'director', 'author', 'genre', 'duration', 'description',
        'is_premiere', 'is_for_children', 'is_musical', 'is_dance'
    ];

    public static $booleanFields = [
        'is_premiere', 'is_for_children', 'is_musical', 'is_dance'
    ];

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
                $this->key = Helpers\Models::generateKey($this->title);
            }
        }
    }

    public function absorbDuplicate($duplicate)
    {
        $newTags = R::tag($duplicate);
        R::addTags($this->bean, $newTags);

        $updateShowsQuery = 'update `show` set play_id = :original_id where play_id = :duplicate_id';
        R::exec($updateShowsQuery, [
            'original_id' => $this->bean->id,
            'duplicate_id' => $duplicate->id,
        ]);

        R::trash($duplicate);
    }
}