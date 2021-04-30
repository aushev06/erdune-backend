<?php


namespace App\Blog\Helpers;


class TextHelper
{
    public static function clearHtml($text)
    {
        return htmlspecialchars($text);
    }
}
