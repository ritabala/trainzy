@extends('layouts.home')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-br from-blue-50 to-indigo-100 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Choose Your Perfect Plan</h1>
            <p class="text-xl text-gray-600 mb-8">Find the right plan for your gym management needs</p>
            
            <!-- Billing Toggle -->
            <div class="flex items-center justify-center space-x-4 mb-12">
                <span class="text-sm font-medium text-gray-700">Monthly</span>
                <button id="billing-toggle" class="relative inline-flex h-6 w-11 items-center rounded-full bg-gray-200 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2" role="switch" aria-checked="false">
                    <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform translate-x-1"></span>
                </button>
                <span class="text-sm font-medium text-gray-700">Annual <span class="text-green-600 font-semibold">(Save 20%)</span></span>
            </div>
        </div>
    </div>
</div>

<!-- Pricing Cards -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($pricing as $package)
            @php
                $isPopular = $package->package_type === 'standard';
                $currencySymbol = $package->currency?->symbol ?? '$';
                $decimalPlaces = $package->currency?->decimal_places ?? 2;
                $decimalPoint = $package->currency?->decimal_point ?? '.';
                $thousandsSeparator = $package->currency?->thousands_separator ?? ',';
                
                $monthlyPrice = $package->monthly_price ? $currencySymbol . number_format($package->monthly_price, $decimalPlaces, $decimalPoint, $thousandsSeparator) : 'Free';
                $annualPrice = $package->annual_price ? $currencySymbol . number_format($package->annual_price, $decimalPlaces, $decimalPoint, $thousandsSeparator) : 'Free';
                $lifetimePrice = $package->lifetime_price ? $currencySymbol . number_format($package->lifetime_price, $decimalPlaces, $decimalPoint, $thousandsSeparator) : 'Free';
            @endphp
            
            <div class="relative bg-white rounded-2xl shadow-lg border-2 {{ $isPopular ? 'border-blue-500 ring-4 ring-blue-500/20' : 'border-gray-200' }} hover:shadow-xl transition-all duration-300">
                @if($isPopular)
                    <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                        <span class="bg-blue-600 text-white px-4 py-1 rounded-full text-sm font-semibold">Most Popular</span>
                    </div>
                @endif
                
                <div class="p-8">
                    <!-- Package Header -->
                    <div class="text-center mb-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $package->package_name }}</h3>
                        <p class="text-gray-600">{{ $package->name }}</p>
                    </div>
                    
                    <!-- Pricing -->
                    <div class="text-center mb-8">
                        <div class="monthly-price">
                            <span class="text-4xl font-bold text-gray-900">{{ $monthlyPrice }}</span>
                            <span class="text-gray-600">/month</span>
                        </div>
                        <div class="annual-price hidden">
                            <span class="text-4xl font-bold text-gray-900">{{ $annualPrice }}</span>
                            <span class="text-gray-600">/year</span>
                        </div>
                        <div class="lifetime-price hidden">
                            <span class="text-4xl font-bold text-gray-900">{{ $lifetimePrice }}</span>
                            <span class="text-gray-600">one-time</span>
                        </div>
                    </div>
                    
                    <!-- Features -->
                    <div class="space-y-4 mb-8">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-700">Up to {{ $package->max_members }} members</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-700">Up to {{ $package->max_staff }} staff</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-700">Up to {{ $package->max_classes }} classes</span>
                        </div>
                        
                        @if($package->modules->count() > 0)
                            @foreach($package->modules->take(3) as $module)
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-gray-700">{{ $module->name }}</span>
                                </div>
                            @endforeach
                            
                            @if($package->modules->count() > 3)
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-gray-700">+{{ $package->modules->count() - 3 }} more features</span>
                                </div>
                            @endif
                        @endif
                    </div>
                    
                    <!-- CTA Button -->
                    <div class="text-center">
                        @auth
                            <a href="{{ route('dashboard.index') }}" class="w-full inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white {{ $isPopular ? 'bg-blue-600 hover:bg-blue-700' : 'bg-gray-600 hover:bg-gray-700' }} transition-colors duration-200">
                                Get Started
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="w-full inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white {{ $isPopular ? 'bg-blue-600 hover:bg-blue-700' : 'bg-gray-600 hover:bg-gray-700' }} transition-colors duration-200">
                                Start Free Trial
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Features Comparison -->
<div class="bg-gray-50 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Everything You Need to Manage Your Gym</h2>
            <p class="text-xl text-gray-600">Powerful features to streamline your fitness business</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Feature 1 -->
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Member Management</h3>
                <p class="text-gray-600">Easily manage member profiles, memberships, and attendance tracking.</p>
            </div>
            
            <!-- Feature 2 -->
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-4">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Class Scheduling</h3>
                <p class="text-gray-600">Schedule and manage fitness classes with instructor assignments.</p>
            </div>
            
            <!-- Feature 3 -->
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4">
                    <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Payment Processing</h3>
                <p class="text-gray-600">Secure payment processing with multiple gateway support.</p>
            </div>
            
            <!-- Feature 4 -->
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mb-4">
                    <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Analytics & Reports</h3>
                <p class="text-gray-600">Comprehensive reports and analytics to track your business growth.</p>
            </div>
            
            <!-- Feature 5 -->
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mb-4">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">QR Code Attendance</h3>
                <p class="text-gray-600">Quick and easy attendance tracking with QR code scanning.</p>
            </div>
            
            <!-- Feature 6 -->
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-4">
                    <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Communication Tools</h3>
                <p class="text-gray-600">Send messages and notifications to members and staff.</p>
            </div>
        </div>
    </div>
</div>

<!-- FAQ Section -->
<div class="bg-white py-16">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Frequently Asked Questions</h2>
            <p class="text-xl text-gray-600">Everything you need to know about our pricing</p>
        </div>
        
        <div class="space-y-6">
            <div class="border border-gray-200 rounded-lg">
                <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50">
                    <span class="font-medium text-gray-900">Can I change my plan anytime?</span>
                    <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div class="px-6 pb-4">
                    <p class="text-gray-600">Yes, you can upgrade or downgrade your plan at any time. Changes will be reflected in your next billing cycle.</p>
                </div>
            </div>
            
            <div class="border border-gray-200 rounded-lg">
                <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50">
                    <span class="font-medium text-gray-900">Is there a free trial available?</span>
                    <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div class="px-6 pb-4">
                    <p class="text-gray-600">Yes, we offer a free trial period for all new users. You can explore all features before committing to a paid plan.</p>
                </div>
            </div>
            
            <div class="border border-gray-200 rounded-lg">
                <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50">
                    <span class="font-medium text-gray-900">What payment methods do you accept?</span>
                    <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div class="px-6 pb-4">
                    <p class="text-gray-600">We accept all major credit cards, PayPal, and bank transfers. All payments are processed securely.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CTA Section -->
<div class="bg-blue-600 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold text-white mb-4">Ready to Get Started?</h2>
        <p class="text-xl text-blue-100 mb-8">Join thousands of gyms already using our platform</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            @auth
                <a href="{{ route('dashboard.index') }}" class="inline-flex items-center px-8 py-3 border border-transparent text-base font-medium rounded-lg text-blue-600 bg-white hover:bg-gray-50 transition-colors duration-200">
                    Go to Dashboard
                </a>
            @else
                <a href="{{ route('register') }}" class="inline-flex items-center px-8 py-3 border border-transparent text-base font-medium rounded-lg text-blue-600 bg-white hover:bg-gray-50 transition-colors duration-200">
                    Start Free Trial
                </a>
                <a href="{{ route('login') }}" class="inline-flex items-center px-8 py-3 border border-white text-base font-medium rounded-lg text-white hover:bg-blue-700 transition-colors duration-200">
                    Sign In
                </a>
            @endauth
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const billingToggle = document.getElementById('billing-toggle');
    const monthlyPrices = document.querySelectorAll('.monthly-price');
    const annualPrices = document.querySelectorAll('.annual-price');
    const lifetimePrices = document.querySelectorAll('.lifetime-price');
    
    let isAnnual = false;
    
    billingToggle.addEventListener('click', function() {
        isAnnual = !isAnnual;
        
        // Update toggle appearance
        const toggleSpan = billingToggle.querySelector('span');
        if (isAnnual) {
            billingToggle.setAttribute('aria-checked', 'true');
            billingToggle.classList.add('bg-blue-600');
            toggleSpan.classList.remove('translate-x-1');
            toggleSpan.classList.add('translate-x-5');
        } else {
            billingToggle.setAttribute('aria-checked', 'false');
            billingToggle.classList.remove('bg-blue-600');
            toggleSpan.classList.remove('translate-x-5');
            toggleSpan.classList.add('translate-x-1');
        }
        
        // Toggle price visibility
        monthlyPrices.forEach(price => {
            price.classList.toggle('hidden', isAnnual);
        });
        annualPrices.forEach(price => {
            price.classList.toggle('hidden', !isAnnual);
        });
    });
});
</script>
@endsection