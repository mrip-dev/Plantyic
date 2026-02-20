<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ============================================
        // 1️⃣ CREATE Plantyic SPECIFIC PERMISSIONS
        // ============================================

        $permissions = [
            // Dashboard & Overview
            'view-dashboard',

            // ===== ADMIN PERMISSIONS =====
            // User Management
            'manage-users',
            'view-users',
            'create-users',
            'edit-users',
            'delete-users',
            'approve-vendors',
            'suspend-users',

            // Vendor Management
            'manage-vendors',
            'view-vendors',
            'create-vendors',
            'edit-vendors',
            'delete-vendors',
            'view-vendor-documents',
            'manage-vendor-packages',

            // Service Management
            'manage-services',
            'view-services',
            'create-services',
            'edit-services',
            'delete-services',
            'manage-service-categories',

            // Booking & Job Management
            'manage-bookings',
            'view-all-bookings',
            'assign-jobs',
            'reassign-jobs',
            'cancel-bookings',
            'view-job-tracking',

            // Package Management
            'manage-packages',
            'view-packages',
            'create-packages',
            'edit-packages',
            'delete-packages',

            // Financial Management
            'manage-finance',
            'view-transactions',
            'process-refunds',
            'manage-commissions',
            'generate-invoices',
            'view-reports',
            'export-reports',

            // Content Management
            'manage-blogs',
            'manage-faqs',
            'manage-testimonials',
            'manage-pages',

            // System Management
            'manage-settings',
            'manage-notifications',
            'manage-email-templates',
            'view-activity-logs',
            'manage-tickets',
            'manage-penalties',

            // ===== VENDOR PERMISSIONS =====
            'vendor-dashboard',
            'manage-vendor-profile',
            'edit-vendor-services',
            'view-vendor-jobs',
            'accept-jobs',
            'decline-jobs',
            'start-job-timer',
            'complete-job',
            'view-job-history',
            'manage-vendor-team',
            'add-team-members',
            'manage-vendor-wallet',
            'request-payout',
            'view-vendor-earnings',
            'manage-vendor-schedule',
            'view-customer-reviews',
            'respond-to-reviews',

            // ===== CUSTOMER PERMISSIONS =====
            'customer-dashboard',
            'book-services',
            'view-bookings',
            'cancel-bookings',
            'reschedule-bookings',
            'add-to-favorites',
            'write-reviews',
            'edit-reviews',
            'view-invoices',
            'download-invoices',
            'chat-with-vendor',
            'track-job-live',
            'manage-profile',
            'view-promotions',
            'use-referral-program',

            // ===== SUPERVISOR PERMISSIONS =====
            'supervisor-dashboard',
            'view-assigned-jobs',
            'update-job-status',
            'manage-team-attendance',
            'view-team-performance',
            'submit-job-reports',
        ];

        // Create permissions using updateOrCreate
        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                [
                    'name' => $permission,
                    'guard_name' => 'api'
                ]
            );
        }

        // ============================================
        // 2️⃣ CREATE ROLES FOR Plantyic
        // ============================================

        $roles = [
            'Super Admin' => Permission::where('guard_name', 'api')->pluck('name')->toArray(),

            'Admin' => [
                'view-dashboard',
                'manage-users',
                'view-users',
                'create-users',
                'edit-users',
                'manage-vendors',
                'view-vendors',
                'approve-vendors',
                'manage-services',
                'view-services',
                'create-services',
                'edit-services',
                'manage-bookings',
                'view-all-bookings',
                'assign-jobs',
                'cancel-bookings',
                'manage-packages',
                'view-packages',
                'create-packages',
                'edit-packages',
                'manage-finance',
                'view-transactions',
                'generate-invoices',
                'view-reports',
                'manage-blogs',
                'manage-faqs',
                'manage-settings',
                'view-activity-logs',
                'manage-tickets',
                'manage-penalties',
            ],

            'Staff' => [
                'view-dashboard',
                'view-users',
                'view-vendors',
                'approve-vendors',
                'view-services',
                'edit-services',
                'manage-bookings',
                'view-all-bookings',
                'assign-jobs',
                'view-packages',
                'view-transactions',
                'view-reports',
                'manage-tickets',
            ],

            'Vendor' => [
                'vendor-dashboard',
                'manage-vendor-profile',
                'edit-vendor-services',
                'view-vendor-jobs',
                'accept-jobs',
                'decline-jobs',
                'start-job-timer',
                'complete-job',
                'view-job-history',
                'manage-vendor-team',
                'add-team-members',
                'manage-vendor-wallet',
                'request-payout',
                'view-vendor-earnings',
                'manage-vendor-schedule',
                'view-customer-reviews',
                'respond-to-reviews',
                'chat-with-vendor',
            ],

            'Customer' => [
                'customer-dashboard',
                'book-services',
                'view-bookings',
                'cancel-bookings',
                'reschedule-bookings',
                'add-to-favorites',
                'write-reviews',
                'edit-reviews',
                'view-invoices',
                'download-invoices',
                'chat-with-vendor',
                'track-job-live',
                'manage-profile',
                'view-promotions',
                'use-referral-program',
            ],

            'Supervisor' => [
                'supervisor-dashboard',
                'view-assigned-jobs',
                'update-job-status',
                'manage-team-attendance',
                'view-team-performance',
                'submit-job-reports',
                'chat-with-vendor',
            ],

            'Driver' => [
                'view-assigned-jobs',
                'update-job-status',
                'start-job-timer',
                'complete-job',
            ],

            'Packing Staff' => [
                'view-assigned-jobs',
                'update-job-status',
            ],
            'Worker' => [
                'view-assigned-jobs',
                'update-job-status',
                'start-job-timer',
                'complete-job',
            ]
        ];

        // Create roles using updateOrCreate
        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::updateOrCreate(
                [
                    'name' => $roleName,
                    'guard_name' => 'api'
                ]
            );

            // Get permission IDs for the role
            $permissionIds = Permission::whereIn('name', $rolePermissions)
                ->where('guard_name', 'api')
                ->pluck('id')
                ->toArray();

            // Sync permissions
            $role->syncPermissions($permissionIds);
        }

        // ============================================
        // 3️⃣ CREATE DEMO USERS FOR Plantyic
        // ============================================

        // Create Super Admin
        $superAdmin = User::updateOrCreate(
            ['email' => 'superadmin@plantyic.com'],
            [
                'name' => 'Plantyic Super Admin',
                'password' => bcrypt('password'),
                'role' => 'Super Admin',
                'user_type' => 'admin',
                'phone' => '+971500000001',
                'is_approved' => true,
                'profile_completed' => true,
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
        $superAdmin->syncRoles(['Super Admin']);

        // Create Admin
        $admin = User::updateOrCreate(
            ['email' => 'admin@plantyic.com'],
            [
                'name' => 'Plantyic Admin',
                'password' => bcrypt('password'),
                'role' => 'Admin',
                'user_type' => 'admin',
                'phone' => '+971500000002',
                'is_approved' => true,
                'profile_completed' => true,
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
        $admin->syncRoles(['Admin']);

        // Create Staff
        $staff = User::updateOrCreate(
            ['email' => 'staff@plantyic.com'],
            [
                'name' => 'Plantyic Staff',
                'password' => bcrypt('password'),
                'role' => 'Staff',
                'user_type' => 'staff',
                'phone' => '+971500000003',
                'is_approved' => true,
                'profile_completed' => true,
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
        $staff->syncRoles(['Staff']);

        // Create Vendor (Moving Company)
        $vendor = User::updateOrCreate(
            ['email' => 'vendor.moving@demo.com'],
            [
                'name' => 'Fast Movers LLC',
                'password' => bcrypt('password'),
                'role' => 'Vendor',
                'user_type' => 'vendor_company',
                'vendor_type' => 'moving_company',
                'company_name' => 'Fast Movers LLC',
                'phone' => '+971500000004',
                'trade_license' => 'TRADEMOV001',
                'emirates_id' => '784199012345678',
                'contact_person_name' => 'Ahmed Khan',
                'contact_person_designation' => 'Operations Manager',
                'country' => 'UAE',
                'state' => 'Dubai',
                'service_area' => json_encode(['Dubai', 'Sharjah', 'Abu Dhabi']),
                'address' => 'Business Bay, Dubai, UAE',
                'is_approved' => true,
                'profile_completed' => true,
                'status' => 'active',
                'wallet_balance' => 5000.00,
                'email_verified_at' => now(),
            ]
        );
        $vendor->syncRoles(['Vendor']);

        // Create Customer
        $customer = User::updateOrCreate(
            ['email' => 'customer@demo.com'],
            [
                'name' => 'John Smith',
                'password' => bcrypt('password'),
                'role' => 'Customer',
                'user_type' => 'customer',
                'phone' => '+971500000005',
                'is_approved' => true,
                'profile_completed' => true,
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
        $customer->syncRoles(['Customer']);

        // Create Supervisor (for vendor team)
        $supervisor = User::updateOrCreate(
            ['email' => 'supervisor@fastmovers.com'],
            [
                'name' => 'Mahi Khan',
                'password' => bcrypt('password'),
                'role' => 'Supervisor',
                'user_type' => 'supervisor',
                'phone' => '+971500000006',
                'company_name' => 'Fast Movers LLC',
                'is_approved' => true,
                'profile_completed' => true,
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
        $supervisor->syncRoles(['Supervisor']);


         // Create Worker (for vendor team)
        $supervisor = User::updateOrCreate(
            ['email' => 'worker@fastmovers.com'],
            [
                'name' => 'Worker Khan',
                'password' => bcrypt('password'),
                'role' => 'Worker',
                'user_type' => 'worker',
                'phone' => '+971500000006',
                'company_name' => 'Fast Movers LLC',
                'is_approved' => true,
                'profile_completed' => true,
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
        $supervisor->syncRoles(['Worker']);

        $this->command->info('============================================');
        $this->command->info('Plantyic PERMISSIONS & ROLES CREATED');
        $this->command->info('============================================');
        $this->command->info('Demo Users Created:');
        $this->command->info('Super Admin: superadmin@plantyic.com / password');
        $this->command->info('Admin: admin@plantyic.com / password');
        $this->command->info('Staff: staff@plantyic.com / password');
        $this->command->info('Vendor: vendor.moving@demo.com / password');
        $this->command->info('Customer: customer@demo.com / password');
        $this->command->info('Supervisor: supervisor@fastmovers.com / password');
        $this->command->info('Worker: worker@fastmovers.com / password');
        $this->command->info('============================================');
    }
}
