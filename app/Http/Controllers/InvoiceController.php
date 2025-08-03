<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function __construct()
    {
        abort_if(!has_module_access('invoices'), 403, __('errors.module_access_denied'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('view_invoices'), 403, __('errors.permission_denied'));
        return view('invoice.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('add_invoice'), 403, __('errors.permission_denied'));
        $userId = request('user_id');
        $fromAdd = request('from_add');
        return view('invoice.create', compact('userId', 'fromAdd'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('view_invoices'), 403, __('errors.permission_denied'));
        return view('invoice.show', compact('id'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $invoiceId)
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('edit_invoice'), 403, __('errors.permission_denied'));
        return view('invoice.edit', compact('invoiceId'));
    }
}
