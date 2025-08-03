<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccountSettingsController extends Controller
{
    public function edit()
    {
        return view('account-settings');
    }
}
