<?php

namespace App\Observers;

use App\Models\Gym;
use App\Models\GlobalCurrency;
use App\Models\Currency;
use Illuminate\Support\Str;

class GymObserver
{

    public function creating(Gym $gym)
    {
        $gym->slug = Str::slug($gym->name);
    }
    
    public function created(Gym $gym)
    {

        $globalCurrency = GlobalCurrency::all();

        foreach ($globalCurrency as $currency) {
            Currency::create([
                'gym_id' => $gym->id,
                'name' => $currency->name,
                'symbol' => $currency->symbol,
                'code' => $currency->code,
                'decimal_places' => $currency->decimal_places,
                'decimal_point' => $currency->decimal_point,
                'thousands_separator' => $currency->thousands_separator,
            ]);
        }

        $gym->update([
            'currency_id' => Currency::where('gym_id', $gym->id)->first()->id,
        ]);
    
    }
}
