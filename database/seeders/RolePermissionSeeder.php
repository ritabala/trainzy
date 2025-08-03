<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Models\Gym;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gyms = Gym::all();
 
        foreach ($gyms as $gym) {
            $roles['admin-' . $gym->id] = 'Admin';
            $roles['staff-' . $gym->id] = 'Staff';
            $roles['member-' . $gym->id] = 'Member';
        }
    
        // Define permissions with display names
        $permissions = [
            // Manage Roles and Permissions
            'manage_roles' => 'Manage Roles',
            'manage_permissions' => 'Manage Permissions',

            // Dashboard
            'view_dashboard' => 'View Dashboard',

            // Services
            'view_services' => 'View Services',
            'edit_service' => 'Edit Service',
            'delete_service' => 'Delete Service',
            'add_service' => 'Add Service',

            // Activity Classes
            'view_activity_classes' => 'View Activity Classes',
            'edit_activity_class' => 'Edit Activity Class',
            'add_activity_class' => 'Add Activity Class',
            'delete_activity_class' => 'Delete Activity Class',
            'schedule_activity_class' => 'Schedule Activity Class',

            // Staff Schedule
            'view_staff_schedule' => 'View Staff Schedule',

            // Memberships
            'view_memberships' => 'View Memberships',
            'add_membership' => 'Add Membership',
            'delete_membership' => 'Delete Membership',
            'edit_membership' => 'Edit Membership',

            // User Memberships
            'view_user_memberships' => 'View User Memberships',

            // Members
            'view_members' => 'View Members',
            'add_member' => 'Add Member',
            'edit_member' => 'Edit Member',
            'delete_member' => 'Delete Member',

            // Invoices
            'view_invoices' => 'View Invoices',
            'edit_invoice' => 'Edit Invoice',
            'delete_invoice' => 'Delete Invoice',
            'add_invoice' => 'Add Invoice',
            'make_payment' => 'Make Payment',

            // Payments
            'view_payments' => 'View Payments',
            'add_payment' => 'Add Payment',
            'edit_payment' => 'Edit Payment',
            'delete_payment' => 'Delete Payment',
            'download_payment_receipt' => 'Download Payment Receipt',

            // Staff
            'view_staff' => 'View Staff',
            'edit_staff' => 'Edit Staff',
            'delete_staff' => 'Delete Staff',
            'add_staff' => 'Add Staff',
            'change_password' => 'Change Password',

            // Reports
            'view_reports' => 'View Reports',
            'view_membership_reports' => 'View Membership Reports',
            'view_revenue_reports' => 'View Revenue Reports',

            // Settings
            'manage_settings' => 'Manage Settings',

            // Messages
            'view_messages' => 'View Messages',
            'edit_message' => 'Edit Message',
            'delete_message' => 'Delete Message',
            'add_message' => 'Add Message',

            // Attendance
            'view_staff_attendance' => 'View Staff Attendance',
            'edit_staff_attendance' => 'Edit Staff Attendance',
            'delete_staff_attendance' => 'Delete Staff Attendance',
            'add_staff_attendance' => 'Add Staff Attendance',

            'view_member_attendance' => 'View Member Attendance',
            'edit_member_attendance' => 'Edit Member Attendance',
            'delete_member_attendance' => 'Delete Member Attendance',
            'add_member_attendance' => 'Add Member Attendance',

            'scan_qr_code' => 'Scan QR Code',
            'download_qr_code' => 'Download QR Code',
            'view_qr_codes' => 'View QR Codes',

            // Billing
            'manage_billing' => 'Manage Billing',
            'upgrade_plan' => 'Upgrade Plan',
        ];

        // Create permissions if they don't exist
        foreach ($permissions as $name => $displayName) {
            Permission::firstOrCreate([
                'name' => $name,
                'display_name' => $displayName
            ]);
        }
    
        // Create roles and assign permissions
        foreach ($roles as $roleName => $displayName) {
            $role = Role::firstOrCreate([
                'name' => $roleName,
                'display_name' => $displayName
            ]);
    

            // Grant full permissions to Admin (except super-admin permissions)
            if ($displayName === 'Admin') {
                $role->syncPermissions(array_keys($permissions));
            }
    
            // Staff gets some permissions
            if ($displayName === 'Staff') {
                $role->syncPermissions([
                    'view_dashboard',
                    'view_services',
                    'view_activity_classes',
                    'view_staff_schedule',
                    'view_memberships',
                    'view_members',
                    'add_member',
                    'edit_member',
                    'view_invoices',
                    'edit_invoice',
                    'add_invoice',
                    'add_payment',
                    'view_payments',
                    'edit_payment',
                    'download_payment_receipt',
                    'manage_settings',
                    'view_messages',
                    'edit_message',
                    'delete_message',
                    'add_message',
                    'view_staff_attendance',
                    'edit_staff_attendance',
                    'delete_staff_attendance',
                    'add_staff_attendance',
                    'view_member_attendance',
                    'edit_member_attendance',
                    'delete_member_attendance',
                    'add_member_attendance',
                    'scan_qr_code',
                    'download_qr_code',
                    'view_qr_codes',
                    'manage_billing',
                ]);
            }
    
            // Member role usually has no permissions by default
        }

  
        foreach ($gyms as $gym) {
            $this->createUserWithRole('admin-' . $gym->id . '@example.com', 'admin-' . $gym->id, 'admin-' . $gym->id, $gym->id);
        }
    }

    /**
     * Helper function to create a user and assign a role if the user does not exist.
     */
    private function createUserWithRole($email, $name, $roleName, $gymId = null)
    {
        // Debugging output to see if the user exists before insertion
        $existingUser = User::where('email', $email)->first();

        if ($existingUser) {
            \Log::info("User with email {$email} already exists, skipping creation.");
        } else {
            // If user does not exist, create the user
            $user = User::create([
                'gym_id' => $gymId,
                'name' => $name,
                'email' => $email,
                'password' => bcrypt('password123'),
                'email_verified_at' => now(),
                'is_active' => 1,
            ]);

            // Assign the role to the user
            $user->assignRole($roleName);

            // Log the user creation
            \Log::info("User with email {$email} created and assigned role {$roleName}.");
        }
    }
}
