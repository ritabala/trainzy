<?php

namespace App\Livewire\Settings\Tax;

use Livewire\Component;
use App\Models\Tax;

class CreateEditTax extends Component
{
    public $tax_name;
    public $tax_percent;
    public $isEditing = false;
    public $tax_id;

    protected $rules = [
        'tax_name' => 'required|min:3|max:255',
        'tax_percent' => 'required|numeric|min:0|max:100',
    ];

    public function mount($tax = null)
    {
        if ($tax) {
            $this->editTax($tax);
        }
    }

    public function editTax($tax)
    {
        $this->isEditing = true;
        $this->tax_id = $tax->id;
        $this->tax_name = $tax->tax_name;
        $this->tax_percent = $tax->tax_percent;
    }

    public function save()
    {
        $this->validate();

        try {
            if ($this->isEditing) {
                $tax = Tax::findOrFail($this->tax_id);
                $tax->update([
                'tax_name' => $this->tax_name,
                'tax_percent' => $this->tax_percent,
                ]);
                session()->flash('message', __('products.tax_updated'));
            } else {
                Tax::create([
                    'tax_name' => $this->tax_name,
                    'tax_percent' => $this->tax_percent,
                ]);
                session()->flash('message', __('products.tax_created'));
            }

            return redirect()->route('settings.taxes.index');
        } catch (\Exception $e) {
            session()->flash('error', __('products.failed_to_save_tax') . ' ' . $e->getMessage());
            return;
        }
    }

    public function render()
    {
        return view('livewire.settings.tax.create-edit-tax');
    }
} 