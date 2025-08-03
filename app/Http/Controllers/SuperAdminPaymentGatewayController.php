<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SuperAdminPaymentGatewayController extends Controller
{
    public function index()
    {
        return view('superadmin-settings.payment_gateways.index');
    }
}
