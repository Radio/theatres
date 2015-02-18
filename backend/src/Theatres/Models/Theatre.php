<?php

namespace Theatres\Models;

use Theatres\Collections\Theatres;
use RedBean_Facade as R;
use Theatres\Core\Exceptions\Fetchers_UndefinedFetcher;

/**
 * Class Theatre
 *
 * @property $id
 * @property $title
 * @property $abbr
 * @property $link
 * @property $key
 * @property $has_fetcher
 * @property $house_slug
 *
 * @package Theatres\Models
 */
class Theatre extends \RedBean_SimpleModel
{
    const HOUSE_ABBR = 'ДА';
    const HOUSE_TITLE_ADDITION = 'в Доме Актера';

    public static $allowedFields = array(
        'title', 'abbr', 'link', 'has_fetcher', 'key', 'house_slug'
    );

    public function loadByKey($key)
    {
        $theatres = new Theatres();
        $theatres->setConditions('`key` = ?', array($key));

        $first = $theatres->getFirst();
        if ($first) {
            $this->bean->importFrom($first);
        }
    }

    public function isInHouse()
    {
        return (bool) $this->house_slug;
    }

    public function getAbbr($full = false)
    {
        return $this->abbr . ($full && $this->isInHouse() ? ' (' . self::HOUSE_ABBR . ')' : '');
    }

    public function getTitle($full = false)
    {
        return $this->title . ($full && $this->isInHouse() ? ' (' . self::HOUSE_TITLE_ADDITION . ')' : '');
    }
}