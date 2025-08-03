<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\Models\ActivityClass;
use App\Observers\ActivityClassObserver;
use App\Models\AvailableTimeSlot;
use App\Observers\AvailableTimeSlotObserver;
use App\Models\BodyMeasurementValue;
use App\Observers\BodyMeasurementValueObserver;
use App\Models\BodyMeasurement;
use App\Observers\BodyMeasurementObserver;
use App\Models\BodyMetricTarget;
use App\Observers\BodyMetricTargetObserver;
use App\Models\BodyMetricType;
use App\Observers\BodyMetricTypeObserver;
use App\Models\Currency;
use App\Observers\CurrencyObserver;
use App\Models\Document;
use App\Observers\DocumentObserver;
use App\Models\Frequency;
use App\Observers\FrequencyObserver;
use App\Models\Invoice;
use App\Observers\InvoiceObserver;
use App\Models\InvoiceDetailTax;
use App\Observers\InvoiceDetailTaxObserver;
use App\Models\InvoiceDetail;
use App\Observers\InvoiceDetailObserver;
use App\Models\MemberEnrollment;
use App\Observers\MemberEnrollmentObserver;
use App\Models\MembershipActivityClass;
use App\Observers\MembershipActivityClassObserver;
use App\Models\MembershipFrequency;
use App\Observers\MembershipFrequencyObserver;
use App\Models\MembershipService;
use App\Observers\MembershipServiceObserver;
use App\Models\Membership;
use App\Observers\MembershipObserver;
use App\Models\Payment;
use App\Observers\PaymentObserver;
use App\Models\ProductTax;
use App\Observers\ProductTaxObserver;
use App\Models\Product;
use App\Observers\ProductObserver;
use App\Models\ProgressPhoto;
use App\Observers\ProgressPhotoObserver;
use App\Models\RecurringSession;
use App\Observers\RecurringSessionObserver;
use App\Models\Service;
use App\Observers\ServiceObserver;
use App\Models\StaffDetail;
use App\Observers\StaffDetailObserver;
use App\Models\StaffType;
use App\Observers\StaffTypeObserver;
use App\Models\Tax;
use App\Observers\TaxObserver;
use App\Models\UserMembership;
use App\Observers\UserMembershipObserver;
use App\Models\User;
use App\Observers\UserObserver;
use App\Models\Message;
use App\Observers\MessageObserver;
use Carbon\Carbon;
use App\Models\Gym;
use App\Observers\GymObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Carbon::setLocale(app()->getLocale());

        Blade::if('hasCachedPermission', function ($permission) {
            $user = auth()->user();
            return $user && $user->getCachedPermissions()->contains($permission);
        });

        Blade::if('hasCachedRole', function ($role) {
            $user = auth()->user();
            return $user && $user->getCachedRoles()->contains($role);
        });

        ActivityClass::observe(ActivityClassObserver::class);
        AvailableTimeSlot::observe(AvailableTimeSlotObserver::class);
        BodyMeasurementValue::observe(BodyMeasurementValueObserver::class);
        BodyMeasurement::observe(BodyMeasurementObserver::class);
        BodyMetricTarget::observe(BodyMetricTargetObserver::class);
        BodyMetricType::observe(BodyMetricTypeObserver::class);
        Currency::observe(CurrencyObserver::class);
        Document::observe(DocumentObserver::class);
        Frequency::observe(FrequencyObserver::class);
        Invoice::observe(InvoiceObserver::class);
        InvoiceDetailTax::observe(InvoiceDetailTaxObserver::class);
        InvoiceDetail::observe(InvoiceDetailObserver::class);
        MemberEnrollment::observe(MemberEnrollmentObserver::class);
        MembershipActivityClass::observe(MembershipActivityClassObserver::class);
        MembershipFrequency::observe(MembershipFrequencyObserver::class);
        MembershipService::observe(MembershipServiceObserver::class);
        Membership::observe(MembershipObserver::class);
        Payment::observe(PaymentObserver::class);
        ProductTax::observe(ProductTaxObserver::class);
        Product::observe(ProductObserver::class);
        ProgressPhoto::observe(ProgressPhotoObserver::class);
        RecurringSession::observe(RecurringSessionObserver::class);
        Service::observe(ServiceObserver::class);
        StaffDetail::observe(StaffDetailObserver::class);
        StaffType::observe(StaffTypeObserver::class);
        Tax::observe(TaxObserver::class);
        UserMembership::observe(UserMembershipObserver::class);
        User::observe(UserObserver::class);
        Message::observe(MessageObserver::class);
        Gym::observe(GymObserver::class);
    }
}
