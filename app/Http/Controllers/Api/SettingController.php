<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Helpers\SettingHelper;
use App\Http\Controllers\Controller;

class SettingController extends Controller
{
    public function getHelperFunctions($key){
        return SettingHelper::setting($key);
    }
}
