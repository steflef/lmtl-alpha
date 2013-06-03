<?php
namespace CQAtlas\Helpers;

/**
 * Class CqUtil
 * @package CQAtlas\Helpers
 */
abstract class CqUtil
{

    public function __construct (){}

    /**
     * @param $text
     * @return mixed|string
     */
    static public function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);

        // trim
        $text = trim($text, '-');

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // lowercase
        $text = strtolower($text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        if (empty($text))
        {
            return 'n-a';
        }

        return $text;
    }

    /**
     * @param $keys
     * @param $lookup
     * @return bool
     */
    static function matchKeys($keys, $lookup)
    {
        foreach($lookup as $item){
            foreach ($keys as $key) {
                if(mb_strtolower($key) === $item){
                    return $key;
                }
            }
        }
        return false;
    }
}