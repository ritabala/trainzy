@extends('layouts.home')

@section('content')
    <div class="bg-gradient-to-br from-blue-50 to-indigo-100 py-16">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">How {{ global_settings()->app_name }} Works</h1>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">Discover how our platform connects fitness enthusiasts with the perfect gym while helping gym owners grow their business.</p>
            </div>

            <!-- For Members Section -->
            <div class="mb-16">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">For Fitness Enthusiasts</h2>
                    <p class="text-lg text-gray-600">Find your perfect gym match in just a few clicks</p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <!-- Step 1 -->
                    <div class="bg-white rounded-xl shadow-lg p-8 text-center hover:shadow-xl transition-shadow duration-300">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">1. Search & Discover</h3>
                        <p class="text-gray-600">Enter your location and browse through hundreds of gyms. Filter by price, facilities, ratings, and more to find your perfect match.</p>
                    </div>

                    <!-- Step 2 -->
                    <div class="bg-white rounded-xl shadow-lg p-8 text-center hover:shadow-xl transition-shadow duration-300">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">2. Compare & Choose</h3>
                        <p class="text-gray-600">Compare membership plans, facilities, and reviews. See detailed pricing, class schedules, and amenities to make an informed decision.</p>
                    </div>

                    <!-- Step 3 -->
                    <div class="bg-white rounded-xl shadow-lg p-8 text-center hover:shadow-xl transition-shadow duration-300">
                        <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">3. Join & Start</h3>
                        <p class="text-gray-600">Book a free trial or join directly. Get instant access to your new gym and start your fitness journey with confidence.</p>
                    </div>
                </div>
            </div>

            <!-- For Gym Owners Section -->
            <div class="mb-16">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">For Gym Owners</h2>
                    <p class="text-lg text-gray-600">Grow your business with our comprehensive management platform</p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <!-- Step 1 -->
                    <div class="bg-white rounded-xl shadow-lg p-8 text-center hover:shadow-xl transition-shadow duration-300">
                        <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">1. Set Up Your Gym</h3>
                        <p class="text-gray-600">Create your gym profile, add facilities, upload photos, and set up membership plans. Get started with a free trial.</p>
                    </div>

                    <!-- Step 2 -->
                    <div class="bg-white rounded-xl shadow-lg p-8 text-center hover:shadow-xl transition-shadow duration-300">
                        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">2. Manage Everything</h3>
                        <p class="text-gray-600">Handle memberships, payments, class schedules, staff management, and attendance tracking all from one dashboard.</p>
                    </div>

                    <!-- Step 3 -->
                    <div class="bg-white rounded-xl shadow-lg p-8 text-center hover:shadow-xl transition-shadow duration-300">
                        <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">3. Grow Your Business</h3>
                        <p class="text-gray-600">Get discovered by new members, increase retention with automated features, and scale your operations efficiently.</p>
                    </div>
                </div>
            </div>

            <!-- Key Features Section -->
            <div class="mb-16">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Platform Features</h2>
                    <p class="text-lg text-gray-600">Everything you need for a successful fitness business</p>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Feature 1 -->
                    <div class="bg-white rounded-lg shadow-md p-6 text-center">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Analytics & Reports</h3>
                        <p class="text-sm text-gray-600">Track revenue, membership growth, and business performance</p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="bg-white rounded-lg shadow-md p-6 text-center">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Payment Processing</h3>
                        <p class="text-sm text-gray-600">Secure payment gateways with automatic billing</p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="bg-white rounded-lg shadow-md p-6 text-center">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Member Management</h3>
                        <p class="text-sm text-gray-600">Complete member profiles with attendance tracking</p>
                    </div>

                    <!-- Feature 4 -->
                    <div class="bg-white rounded-lg shadow-md p-6 text-center">
                        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Class Scheduling</h3>
                        <p class="text-sm text-gray-600">Easy class management with booking system</p>
                    </div>

                    <!-- Feature 5 -->
                    <div class="bg-white rounded-lg shadow-md p-6 text-center">
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">QR Code Attendance</h3>
                        <p class="text-sm text-gray-600">Quick check-ins with QR code scanning</p>
                    </div>

                    <!-- Feature 6 -->
                    <div class="bg-white rounded-lg shadow-md p-6 text-center">
                        <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Communication</h3>
                        <p class="text-sm text-gray-600">Built-in messaging system for members</p>
                    </div>

                    <!-- Feature 7 -->
                    <div class="bg-white rounded-lg shadow-md p-6 text-center">
                        <div class="w-12 h-12 bg-teal-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Invoicing</h3>
                        <p class="text-sm text-gray-600">Professional invoice generation and management</p>
                    </div>

                    <!-- Feature 8 -->
                    <div class="bg-white rounded-lg shadow-md p-6 text-center">
                        <div class="w-12 h-12 bg-pink-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Body Metrics</h3>
                        <p class="text-sm text-gray-600">Track member progress and measurements</p>
                    </div>
                </div>
            </div>



            <!-- CTA Section -->
            <div class="text-center">
                <div class="bg-white rounded-2xl shadow-xl p-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Ready to Get Started?</h2>
                    <p class="text-lg text-gray-600 mb-8 max-w-2xl mx-auto">Join thousands of gym owners who are already growing their business with {{ global_settings()->app_name }}. Start your free trial today!</p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('register') }}" class="bg-blue-600 text-white px-8 py-4 rounded-lg font-semibold hover:bg-blue-700 transition-colors text-lg">Start Free Trial</a>
                        <a href="#" class="border-2 border-gray-300 text-gray-700 px-8 py-4 rounded-lg font-semibold hover:border-gray-400 transition-colors text-lg">Contact Sales</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection