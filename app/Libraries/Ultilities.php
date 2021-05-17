<?php

namespace App\Libraries;

use DateTime;
use Carbon\Carbon;
use Akaunting\Money\Money;
use Twilio\Rest\Client;
use Twilio\Jwt\AccessToken;

class Ultilities
{
    public static function clearXSS($string)
    {
        $string = nl2br($string);
        $string = trim(strip_tags($string));
        $string = self::removeScripts($string);

        return $string;
    }

    public static function removeScripts($str)
    {
        $regex =
            '/(<link[^>]+rel="[^"]*stylesheet"[^>]*>)|'.
            '<script[^>]*>.*?<\/script>|'.
            '<style[^>]*>.*?<\/style>|'.
            '<!--.*?-->/is';

        return preg_replace($regex, '', $str);
    }
}
