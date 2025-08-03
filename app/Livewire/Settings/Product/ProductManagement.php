<?php

namespace App\Livewire\Settings\Product;

use Livewire\Component;

class ProductManagement extends Component
{
    public function addProduct()
    {
        return redirect()->route('settings.products.create');
    }

    public function render()
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('manage_settings'), 403);
        return view('livewire.settings.product.product-management');
    }
}
