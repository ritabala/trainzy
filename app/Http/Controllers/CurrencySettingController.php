<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CurrencySettingController extends Controller
{
    public function index()
    {
        return view('settings.currencies.index');
    }
}
