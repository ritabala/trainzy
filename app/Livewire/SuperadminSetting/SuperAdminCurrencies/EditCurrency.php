<?php

namespace App\Livewire\SuperadminSetting\SuperAdminCurrencies;

use Livewire\Component;
use App\Models\GlobalCurrency;
use Jantinnerezo\LivewireAlert\LivewireAlert;
class EditCurrency extends Component
{
    use LivewireAlert;

    public $currency;
    public $name;
    public $code;
    public $symbol;
    public $decimal_places;
    public $decimal_point;
    public $thousands_separator;
    public $showModal = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'code' => 'required|string|max:3',
        'symbol' => 'required|string|max:10',
        'decimal_places' => 'required|integer|min:0|max:10',
        'decimal_point' => 'required|string|max:1',
        'thousands_separator' => 'required|string|max:1',
    ];

    public function mount(GlobalCurrency $currency = null)
    {
        if ($currency->exists) {
            $this->currency = $currency;
            $this->name = $currency->name;
            $this->code = $currency->code;
            $this->symbol = $currency->symbol;
            $this->decimal_places = $currency->decimal_places;
            $this->decimal_point = $currency->decimal_point;
            $this->thousands_separator = $currency->thousands_separator;
        }
    }

    public function save()
    {
        $this->validate();

        if ($this->currency) {
            $this->currency->update([
                'name' => $this->name,
                'code' => $this->code,
                'symbol' => $this->symbol,
                'decimal_places' => $this->decimal_places,
                'decimal_point' => $this->decimal_point,
                'thousands_separator' => $this->thousands_separator,
            ]);

            $this->dispatch('currencyUpdated');
            $this->dispatch('close-edit-currency');
        }
    }

    public function render()
    {
        return view('livewire.superadmin-setting.super-admin-currencies.edit-currency');
    }
}
