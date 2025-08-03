<?php

namespace App\Livewire\Settings\Product;

use App\Models\Product;
use App\Models\Tax;
use Livewire\Component;

class CreateEditProduct extends Component
{

    public $name;
    public $product_code;
    public $price;
    public $quantity;
    public $expiry_date;
    public $selectedTaxes = [];
    public $editingProductId;
    public $isEditing = false;
    public $description;

    protected $rules = [
        'name' => 'required|string|max:255',
        'product_code' => 'required|string|max:50',
        'price' => 'required|numeric|min:0',
        'quantity' => 'required|integer|min:0',
        'expiry_date' => 'nullable|date',
        'selectedTaxes' => 'nullable|array',
        'selectedTaxes.*' => 'exists:taxes,id',
        'description' => 'string|max:255',
    ];

    public function mount($product = null)
    {
        if ($product) {
            $this->editProduct($product);
        }
    }

    public function editProduct($product)
    {
        $this->isEditing = true;
        $this->editingProductId = $product['id'];
        $this->name = $product['name'];
        $this->product_code = $product['product_code'];
        $this->description = $product['description'];
        $this->price = $product['price'];
        $this->quantity = $product['quantity'];
        $this->expiry_date = $product['expiry_date'] ? date('Y-m-d', strtotime($product['expiry_date'])) : null;
        $this->selectedTaxes = collect($product['taxes'])->pluck('id')->toArray();
    }

    public function store()
    {
        $this->validate();

        try {
            $product = Product::create([
                'name' => $this->name,
                'product_code' => $this->product_code,
                'description' => $this->description,
                'price' => $this->price,
                'quantity' => $this->quantity,
                'expiry_date' => $this->expiry_date,
            ]);

            if (!empty($this->selectedTaxes)) {
                $product->taxes()->sync($this->selectedTaxes);
            }

            session()->flash('message', __('products.created'));
            return $this->redirect(route('settings.products.index'));
        } catch (\Exception $e) {
            session()->flash('error', __('products.failed_to_create_product') . $e->getMessage());
            return;
        }
    }

    public function update()
    {
        $this->validate();

        try {
            $product = Product::find($this->editingProductId);
            $product->update([
                'name' => $this->name,
                'product_code' => $this->product_code,
                'description' => $this->description,
                'price' => $this->price,
                'quantity' => $this->quantity,
                'expiry_date' => $this->expiry_date,
            ]); 

            $product->taxes()->sync($this->selectedTaxes ?? []);

            session()->flash('message', __('products.updated'));   
            return $this->redirect(route('settings.products.index'));
        } catch (\Exception $e) {
            session()->flash('error', __('products.failed_to_update_product') . $e->getMessage());
            return;
        }
    }

    public function render()
    {
        return view('livewire.settings.product.create-edit-product', [
            'taxes' => Tax::all()
        ]);
    }
} 