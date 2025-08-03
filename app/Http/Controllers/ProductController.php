<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('manage_settings'), 403);
        return view('settings.products.index');
    }

    public function create()
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('manage_settings'), 403);
        return view('settings.products.create');
    }

    public function edit(Product $product)
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('manage_settings'), 403);
        return view('settings.products.edit', compact('product'));
    }
} 