<?php

namespace App\Livewire\SuperadminSetting;

use Livewire\Component;
use App\Models\GlobalSetting;
use App\Models\GlobalCurrency;
use Livewire\WithFileUploads;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class AppSetting extends Component
{
    use LivewireAlert;
    use WithFileUploads;

    public $appSettings;
    public $currencies;
    public $currency;
    public $appLogo;
    // public $locale;
    public $appName;
    
    public function mount()
    {
        $this->currencies = GlobalCurrency::all();
        $this->appSettings = GlobalSetting::first();
    
        $this->currency = $this->appSettings->currency_id;
        // $this->locale = $this->appSettings->locale;
        $this->appName = $this->appSettings->app_name;
    }

    public function updateAppSettings()
    {
        $this->validate([
            'appName' => 'required|string',
            'appLogo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            // 'locale' => 'required',
            'currency' => 'required',
        ]);

        if ($this->appLogo) {
            $this->appLogo = $this->appLogo->store('app_logo', 'public');
        }

        $this->appSettings->update([
            'app_name' => $this->appName,
            'app_logo' => $this->appLogo ?? $this->appSettings->app_logo,
            'currency_id' => $this->currency,
            // 'locale' => $this->locale,
        ]);

        cache()->forget('global_settings');
        $this->redirect(route('superadmin-settings.superadmin_app_settings.index'));

        $this->alert('success', __('settings.app.saved'));
    }

    public function render()
    {
        return view('livewire.superadmin-setting.app-setting');
    }
}
