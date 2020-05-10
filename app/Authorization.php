<?php

namespace App;

class Authorization
{
    public static function authorize($money)
    {
        if ($money >= 100) {
            return false;
        } else {
            return true;
        }
    }

    public static function wallet($wallet,$money)
    {
        if ($wallet > $money || $wallet == $money) {
            return true;
        } else {
            return false;
        }
    }


}
