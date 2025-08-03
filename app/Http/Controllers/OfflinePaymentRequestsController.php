<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OfflinePaymentRequestsController extends Controller
{
    public function index()
    {
        return view('offline-payment-requests.index');
    }
}
