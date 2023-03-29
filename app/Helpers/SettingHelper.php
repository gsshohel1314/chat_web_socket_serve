<?php

namespace App\Helpers;

use App\Models\Setting;
class SettingHelper
{
    // if (!function_exists('setting')) {
        public static function setting($key, $default = null)
        {
            return Setting::getByKey($key, $default);
        }
    // }
}
