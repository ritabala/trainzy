@extends('layouts.home')

@section('content')
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-16">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl font-bold mb-4">Contact Us</h1>
            <p class="text-xl text-blue-100 max-w-2xl mx-auto">Get in touch with our team. We're here to help you with any questions about our fitness management system.</p>
        </div>
    </div>

    <!-- Contact Information -->
    <div class="container mx-auto px-4 py-16 max-w-4xl">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Email Contact -->
            <div class="bg-white p-8 rounded-lg shadow-md border border-gray-200">
                <div class="flex items-center mb-6">
                    <div class="bg-blue-100 p-3 rounded-full">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="ml-3 text-xl font-semibold text-gray-900">Email Us</h3>
                </div>
                <div class="space-y-4">
                    <div>
                        <p class="text-gray-600 mb-1">General inquiries:</p>
                        <a href="mailto:info@example.com" class="text-blue-600 hover:text-blue-800 font-medium">info@example.com</a>
                    </div>
                </div>
            </div>

            <!-- Phone Contact -->
            <div class="bg-white p-8 rounded-lg shadow-md border border-gray-200">
                <div class="flex items-center mb-6">
                    <div class="bg-green-100 p-3 rounded-full">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                    </div>
                    <h3 class="ml-3 text-xl font-semibold text-gray-900">Call Us</h3>
                </div>
                <div class="space-y-4">
                    <div>
                        <p class="text-gray-600 mb-1">Phone:</p>
                        <a href="tel:+1-555-123-4567" class="text-blue-600 hover:text-blue-800 font-medium">+1 (555) 123-4567</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Office Address -->
        <div class="mt-12">
            <div class="bg-white p-8 rounded-lg shadow-md border border-gray-200 max-w-2xl mx-auto">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">Our Office</h2>
                <div class="flex items-start justify-center">
                    <svg class="h-6 w-6 text-gray-400 mt-1 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <div class="text-center">
                        <p class="text-gray-700 text-lg">123 Fitness Street</p>
                        <p class="text-gray-700 text-lg">New York, NY 10001</p>
                        <p class="text-gray-700 text-lg">United States</p>
                    </div>
                </div>
            </div>
        </div>

     
    </div>
@endsection