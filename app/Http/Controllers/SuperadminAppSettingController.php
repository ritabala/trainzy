<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SuperadminAppSettingController extends Controller
{
    public function index()
    {
        return view('superadmin-settings.superadmin_app_settings.index');
    }
}
