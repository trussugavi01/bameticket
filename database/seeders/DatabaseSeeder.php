<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Run seeders in order
        $this->call([
            RolesAndPermissionsSeeder::class,
            EmailTemplateSeeder::class,
            SystemSettingsSeeder::class,
        ]);

        // Create Super Admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@nbhca.org.uk'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        );
        $admin->assignRole('Super Admin');

        // Create test users for each role
        $eventAdmin = User::firstOrCreate(
            ['email' => 'events@nbhca.org.uk'],
            [
                'name' => 'Jane Doe',
                'password' => Hash::make('password'),
                'department' => 'Events',
                'job_title' => 'Event Manager',
                'is_active' => true,
            ]
        );
        $eventAdmin->assignRole('Event Admin');

        $financeAdmin = User::firstOrCreate(
            ['email' => 'finance@nbhca.org.uk'],
            [
                'name' => 'Sarah Jenkins',
                'password' => Hash::make('password'),
                'department' => 'Finance',
                'job_title' => 'Senior Finance Administrator',
                'is_active' => true,
            ]
        );
        $financeAdmin->assignRole('Finance Admin');

        $marketingAdmin = User::firstOrCreate(
            ['email' => 'marketing@nbhca.org.uk'],
            [
                'name' => 'Alex Chen',
                'password' => Hash::make('password'),
                'department' => 'Marketing',
                'job_title' => 'Marketing Manager',
                'is_active' => true,
            ]
        );
        $marketingAdmin->assignRole('Marketing & Data');
    }
}
