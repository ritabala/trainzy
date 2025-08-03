<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SuperAdminCurrencySettingController extends Controller
{
    public function index()
    {
        return view('superadmin-settings.currencies.index');
    }
}
