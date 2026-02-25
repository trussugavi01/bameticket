<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Event Management Permissions
        $eventPermissions = [
            'events.view',
            'events.create',
            'events.edit',
            'events.delete',
            'events.archive',
            'events.publish',
            'speakers.manage',
        ];

        // Ticket Sales Permissions
        $ticketPermissions = [
            'tickets.view',
            'tickets.create',
            'tickets.edit',
            'tickets.issue_manual',
            'tickets.export',
            'pricing.create',
            'pricing.edit',
            'discounts.apply',
        ];

        // Financial Permissions
        $financialPermissions = [
            'revenue.view',
            'revenue.export',
            'refunds.view',
            'refunds.process',
            'invoices.download',
            'payment_gateways.edit',
        ];

        // User & System Permissions
        $systemPermissions = [
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            'roles.manage',
            'audit_logs.view',
            'settings.manage',
            'maintenance.manage',
            'backups.manage',
        ];

        // Sponsorship Permissions
        $sponsorshipPermissions = [
            'sponsors.view',
            'sponsors.create',
            'sponsors.edit',
            'sponsors.delete',
            'tables.manage',
        ];

        // Analytics Permissions
        $analyticsPermissions = [
            'analytics.view',
            'reports.generate',
            'campaigns.manage',
        ];

        // CRM Permissions
        $crmPermissions = [
            'crm.view',
            'crm.sync',
            'crm.configure',
        ];

        // Create all permissions
        $allPermissions = array_merge(
            $eventPermissions,
            $ticketPermissions,
            $financialPermissions,
            $systemPermissions,
            $sponsorshipPermissions,
            $analyticsPermissions,
            $crmPermissions
        );

        foreach ($allPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions
        
        // Super Admin - Full access
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin']);
        $superAdmin->givePermissionTo(Permission::all());

        // Event Admin - Event and ticket management
        $eventAdmin = Role::firstOrCreate(['name' => 'Event Admin']);
        $eventAdmin->givePermissionTo(array_merge(
            $eventPermissions,
            $ticketPermissions,
            $sponsorshipPermissions,
            ['analytics.view', 'reports.generate']
        ));

        // Finance Admin - Financial operations
        $financeAdmin = Role::firstOrCreate(['name' => 'Finance Admin']);
        $financeAdmin->givePermissionTo(array_merge(
            $financialPermissions,
            ['tickets.view', 'tickets.export', 'events.view', 'analytics.view', 'reports.generate', 'audit_logs.view']
        ));

        // Marketing & Data - Campaigns and analytics
        $marketingAdmin = Role::firstOrCreate(['name' => 'Marketing & Data']);
        $marketingAdmin->givePermissionTo(array_merge(
            $analyticsPermissions,
            $crmPermissions,
            ['events.view', 'tickets.view', 'tickets.export', 'sponsors.view']
        ));
    }
}
