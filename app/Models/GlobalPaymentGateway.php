<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GlobalPaymentGateway extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    protected $appends = [
        'stripe_key',
        'stripe_secret',
    ];

    public function getStripeKeyAttribute()
    {
        return $this->stripe_environment === 'test' ? $this->test_stripe_key : $this->live_stripe_key;
    }

    public function getStripeSecretAttribute()
    {
        return $this->stripe_environment === 'test' ? $this->test_stripe_secret : $this->live_stripe_secret;
    }



    // protected $casts = [
    //     'stripe_status' => 'boolean',
    //     'test_stripe_status' => 'boolean',
    //     'offline_method_status' => 'boolean'
    // ];
}
