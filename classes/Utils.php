<?php
namespace Grav\Plugin\FormBuilder;

use Grav\Common\Grav;
use Grav\Common\Page\Collection;
use Grav\Common\Uri;

class Utils
{
    /**
     * This method creates a slug from the id and the name of a property.
     *
     * @param string $str
     * @param false $lower
     * @return string
     */
    public static function slug(string $str): string
    {
        if (function_exists('transliterator_transliterate')) {
            $str = transliterator_transliterate('Any-Latin; Latin-ASCII; NFD; [:Nonspacing Mark:] Remove; NFC;', $str);
        } else {
            $str = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $str);
        }

        $str = mb_strtolower($str);

        $str = preg_replace('/[-\s]+/', '-', $str);
        $str = preg_replace('/[^a-z0-9-]/i', '', $str);
        return trim($str, '-');
    }

}
