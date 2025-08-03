<?php

return [
    // General
    'management' => 'Package Management',
    'add' => 'Add Package',
    'edit' => 'Edit Package',
    'created' => 'Package created successfully.',
    'updated' => 'Package updated successfully.',
    'save_error' => 'Error saving package.',
    'deleted' => 'Package deleted successfully.',
    'delete_error' => 'Error deleting package.',
    'search_placeholder' => 'Search packages...',
    'all_status' => 'All Status',
    'confirm_delete' => 'Are you sure you want to delete this Package?',
    'at_least_one_plan' => 'At least one of Monthly or Annual plan must be enabled.',

    // Form Fields
    'name' => 'Package Name',
    'description' => 'Description',
    'price' => 'Price',
    'duration' => 'Duration',
    'duration_type' => 'Duration Type',
    'is_active' => 'Active',

    // Duration Types
    'duration_types' => [
        'days' => 'Days',
        'weeks' => 'Weeks',
        'months' => 'Months',
        'years' => 'Years',
    ],

    // Table Headers
    'table' => [
        'name' => 'Name',
        'description' => 'Description',
        'price' => 'Price',
        'duration' => 'Duration',
        'status' => 'Status',
        'actions' => 'Actions',
    ],

    // Status
    'status' => [
        'active' => 'Active',
        'inactive' => 'Inactive',
    ],

    // Actions
    'actions' => [
        'edit' => 'Edit Package',
        'delete' => 'Delete Package',
        'view' => 'View Package',
    ],

    // Messages
    'messages' => [
        'delete_confirm' => 'Are you sure you want to delete this package?',
        'no_packages' => 'No packages found.',
    ],

    // Plan Types
    'plan_type' => 'Select Plan Type',
    'paid_plan' => 'Paid Plan',
    'free_plan' => 'Free Plan',
    'paid_plan_desc' => 'Full access to all modules',
    'free_plan_desc' => 'Limited access to basic modules',

    // Package Type
    'package_type' => 'Choose Package Type',
    'standard' => 'Standard',
    'lifetime' => 'Lifetime',

    // Currency
    'currency' => 'Choose Currency',
    'select_currency' => 'Select Currency',

    // Pricing
    'monthly_plan' => 'Monthly Plan',
    'annual_plan' => 'Annual Plan',
    'monthly_plan_price' => 'Monthly Plan Price',
    'annual_plan_price' => 'Annual Plan Price',
    'lifetime_plan_price' => 'Lifetime Plan Price',
    'stripe_price_id' => 'Stripe Price ID',

    // Modules
    'modules' => 'Select Modules',
    'modules_list' => [
        'dashboard' => 'Dashboard',
        'services' => 'Services',
        'activity_classes' => 'Activity Classes',
        'staff_schedule' => 'Staff Schedule',
        'memberships' => 'Memberships',
        'user_memberships' => 'User Memberships',
        'members' => 'Members',
        'staff' => 'Staff',
        'invoices' => 'Invoices',
        'payments' => 'Payments',
        'reports' => 'Reports',
        'role_management' => 'Manage Roles',
        'staff_attendance' => 'Staff Attendance',
        'member_attendance' => 'Member Attendance',
        'settings' => 'Settings',
    ],
    'additional_modules' => 'Select Additional Modules',
    'additional_modules_list' => [
        'download_scan_code' => 'Download Scan Code',
        'scan_attendance' => 'Scanner Attendance',
        'messages' => 'Messages',
        'body_metrics' => 'Body Metrics',
        // Add more as needed
    ],

    // Limits
    'max_members' => 'Max Members',
    'max_staff' => 'Max Staff',
    'max_classes' => 'Max Classes',

    // Trial
    'trial_days' => 'Trial Days',
    'trial_message' => 'Trial Message',
    'notification_before_days' => 'Notification Before Days',

    // Package Subscription History
    'package_subscription_history' => 'Package Subscription History',
    'subscription_history' => 'Subscription History',
    'gym_subscriptions' => 'Gym Subscriptions',
    'search_gym_or_package' => 'Search gym or package...',
    'no_subscriptions_found' => 'No subscriptions found.',

    // Package Details
    'package_name' => 'Package Name',
    'package_details' => 'Package Details',
    'package_limits' => 'Package Limits',
    'subscription_period' => 'Subscription Period',
    'starts_on' => 'Starts On',
    'ends_on' => 'Ends On',
    'no_active_subscription' => 'No active subscription found.',
    'billing_cycle' => 'Billing Cycle',
    'included_features' => 'Included Features',

    // Upgrade Plan
    'upgrade_plan' => 'Upgrade Plan',
    'choose_plan' => 'Choose Plan',
    'current_plan' => 'Current Plan',
    'plan_selected' => 'Plan selected successfully.',
    'package_details' => 'Package Details',
    'select_currency' => 'Select Currency',
    'billing_cycle' => 'Billing Cycle',
    'monthly' => 'Monthly',
    'annual' => 'Annual',
    'lifetime' => 'Lifetime',
    'choose_your_plan' => 'Choose Your Plan',
    'select_plan_description' => 'Select the plan that best suits your needs.',
    'cancel_subscription' => 'Cancel Subscription',
    'stripe_price_not_configured' => 'Stripe price not configured.',

    // Payment Gateway
    'attach_offline_receipt' => 'Attach Offline Receipt',
    'select_payment_method' => 'Select Payment Method',
    'offline_payment' => 'Offline Payment',
    'pay_with_stripe' => 'Pay with Stripe',
    'offline_payment_methods' => 'Offline Payment Methods',
    'offline_payment_requested' => 'Your offline payment request has been submitted. We will review and process it shortly.',
    'payment_method_selection' => 'Please select a payment method to proceed with your subscription.',

    // Payment Status
    'no_payments_found' => 'No payments found.',
    'search_gym_or_package' => 'Search',
    'all_gateways' => 'All Gateways',
    'offline' => 'Offline',
]; 