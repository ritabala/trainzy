<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct()
    {
        abort_if(!has_module_access('payments'), 403, __('errors.module_access_denied'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('view_payments'), 403);
        return view('payment.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('add_payment'), 403);
        return view('payment.create');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('view_payments'), 403);
        return view('payment.show', compact('id'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('edit_payment'), 403);
        return view('payment.edit', compact('id'));
    }
}
