<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ActivityClassController;
use App\Livewire\ActivityClass\StaffSchedule;
use App\Http\Controllers\ManageUserRolesController;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\TaxController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StaffTypeController;
use App\Http\Controllers\BodyMetricTypeController;
use App\Http\Controllers\AppSettingController;
use App\Http\Controllers\CurrencySettingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AccountSettingsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\MembersAttendController;
use App\Http\Controllers\StaffAttendController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\GymController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\WebhookController;

Route::get('/', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    } else {
        return redirect()->route('dashboard.index');
    }
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // Route::get('/dashboard', function () {
    //     return view('dashboard');
    // })->name('dashboard');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    Route::get('/calendar', function () {
        return view('calendar');
    })->name('calendar.index');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
    Route::get('/account-settings', [AccountSettingsController::class, 'edit'])->name('account.settings');
    Route::get('/user-memberships', [MembershipController::class, 'userMemberships'])->name('user-memberships.index');
    Route::resource('memberships', MembershipController::class);
    Route::resource('members', MemberController::class);
    Route::resource('staff', StaffController::class);
    Route::resource('invoices', InvoiceController::class);
    Route::resource('payments', PaymentController::class);
    Route::resource('services', ServiceController::class);
    Route::resource('activity-classes', ActivityClassController::class);
    Route::get('/staff-schedule', [ActivityClassController::class, 'staffSchedule'])->name('staff-schedule.index');
    Route::resource('user-roles', ManageUserRolesController::class);
    Route::resource('messages', MessageController::class);

    // Settings Routes
    Route::prefix('settings')->name('settings.')->middleware(['can:manage_settings'])->group(function () {
        Route::get('/app-settings', [AppSettingController::class, 'index'])->name('app_settings.index');
        
        Route::prefix('billing')->name('billing.')->group(function () {
            Route::get('/', [BillingController::class, 'index'])->name('index');
            Route::get('/upgrade', [BillingController::class, 'upgrade'])->name('upgrade');
            Route::get('/stripe-success', [BillingController::class, 'stripeSuccess'])->name('stripe_success');
        });

        Route::resource('taxes', TaxController::class);
        Route::resource('products', ProductController::class);
        Route::resource('staff_types', StaffTypeController::class);
        Route::resource('body_metrics', BodyMetricTypeController::class);
        Route::resource('currencies', CurrencySettingController::class);
    });

    

    // Reports Routes
    Route::controller(ReportsController::class)->group(function () {
        Route::get('/reports/membership', 'membership')->name('reports.membership');
        Route::get('/reports/revenue', 'revenue')->name('reports.revenue');
    });

    // Attendance Routes
    Route::prefix('attendance')->name('attendance.')->group(function () {
        Route::resource('members', MembersAttendController::class);
        Route::resource('staff', StaffAttendController::class);
        
        // QR Code Routes
        Route::get('/qr-codes', [QrCodeController::class, 'index'])->name('qr-codes');
        Route::get('/qr-codes/record/{token}', [QrCodeController::class, 'recordAttendance'])
            ->middleware(['auth'])
            ->name('qr-codes.record');
    });

    // Private file serving route
    Route::get('/private-files/{path}', function ($path) {
        try {
            return Storage::disk('private')->response($path);
        } catch (\Exception $e) {
            abort(404);
        }
    })->where('path', '.*')->middleware(['auth'])->name('private.files');


});
