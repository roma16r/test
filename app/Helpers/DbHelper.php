<?php
namespace App\Helpers;

class DbHelper
{
    static public function escapeLike($string)
    {
        $search = array('%', '_');
        $replace   = array('\%', '\_');
        return str_replace($search, $replace, $string);
    }
}

