<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QrCodeController extends Controller
{
    public function __construct()
    {
        abort_if(!has_module_access('scan_attendance'), 403, __('errors.module_access_denied'));
    }

    public function index()
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('view_qr_codes'), 403, __('errors.permission_denied'));
        return view('attendance.qr-codes.index');
    }

    public function recordAttendance($token)
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('scan_qr_code'), 403, __('errors.permission_denied'));
        
        return view('attendance.qr-codes.record-attendance', [
            'token' => $token
        ]);
    }
} 