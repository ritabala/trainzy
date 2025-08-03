<?php

namespace App\Livewire\SuperadminSetting\SuperAdminCurrencies;

use Livewire\Component;
use App\Models\GlobalCurrency;

class AddCurrency extends Component
{
    public $name;
    public $code;
    public $symbol;
    public $decimal_places = 2;
    public $decimal_point = '.';
    public $thousands_separator = ',';

    protected $rules = [
        'name' => 'required|string|max:255',
        'code' => 'required|string|max:3|unique:currencies,code',
        'symbol' => 'required|string|max:10',
        'decimal_places' => 'required|integer|min:0|max:10',
        'decimal_point' => 'required|string|max:1',
        'thousands_separator' => 'required|string|max:1',
    ];

    public function save()
    {
        $this->validate();

        GlobalCurrency::create([
            'name' => $this->name,
            'code' => $this->code,
            'symbol' => $this->symbol,
            'decimal_places' => $this->decimal_places,
            'decimal_point' => $this->decimal_point,
            'thousands_separator' => $this->thousands_separator,
        ]);

        $this->dispatch('currencyCreated');
        $this->dispatch('close-add-currency');
        $this->reset();
    }

    public function render()
    {
        return view('livewire.superadmin-setting.super-admin-currencies.add-currency');
    }
}
