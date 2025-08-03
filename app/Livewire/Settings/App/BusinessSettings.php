<?php

namespace App\Livewire\Settings\App;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Currency;
use App\Models\Gym;
use DateTimeZone;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Support\Facades\Cache;

class BusinessSettings extends Component
{  
    use WithFileUploads;
    use LivewireAlert;

    public $gym;
    public $name;
    public $address;
    public $phone;
    public $email;
    public $website;
    public $logo;
    public $favicon;
    public $tempLogo;
    public $tempFavicon;
    public $locale;
    public $currency;
    public $timezone;
    public $currencies;
    public $timezones;

    public function mount()
    {
        $this->name = $this->gym->name;
        $this->address = $this->gym->address;
        $this->phone = $this->gym->phone;
        $this->email = $this->gym->email;
        $this->website = $this->gym->website;
        $this->logo = $this->gym->logo;
        $this->favicon = $this->gym->favicon;
        $this->locale = $this->gym->locale;
        $this->currency = $this->gym->currency_id;
        $this->timezone = $this->gym->timezone;
        $this->currencies = Currency::all();
        $this->timezones = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
    }

    public function save()
    {
        $validated = $this->validate([
            'name' => 'required|min:3',
            'address' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'website' => 'nullable|url',
            'tempLogo' => 'nullable|image|max:1024',
            'tempFavicon' => 'nullable|image|max:1024',
            'locale' => 'required',
            'currency' => 'required',
            'timezone' => 'required',
        ]);

        if ($this->tempLogo) {
            $this->logo = $this->tempLogo->store('logos', 'public');
        }


        $this->gym->update([
            'name' => $this->name,
            'address' => $this->address,
            'phone' => $this->phone,
            'email' => $this->email,
            'website' => $this->website,
            'logo' => $this->logo,
            'locale' => $this->locale,
            'currency_id' => $this->currency,
            'timezone' => $this->timezone,
        ]);

        Cache::forget('gym');
        Cache::forget('currency');
        session()->flash('gym', $this->gym);
        $this->dispatch('refresh-gym');
        $this->alert('success', __('settings.app.saved'));
    }

    public function removeLogo()
    {
        $this->logo = null;
        $this->tempLogo = null;
        $this->gym->update(['logo' => null]);
    }
    
    public function render()
    {
        return view('livewire.settings.app.business-settings');
    }
}
