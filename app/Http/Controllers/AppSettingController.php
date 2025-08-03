<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gym;

class AppSettingController extends Controller
{
    public function index()
    {
        $gym = gym();
        return view('settings.app.index', compact('gym'));
    }
}
