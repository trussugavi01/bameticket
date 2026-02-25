<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SystemSetting;

class SystemSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General Settings
            ['key' => 'site_name', 'value' => 'NBHCA Revenue Engine', 'type' => 'string', 'group' => 'general', 'description' => 'Site name displayed in the admin'],
            ['key' => 'site_tagline', 'value' => 'Event Management Portal', 'type' => 'string', 'group' => 'general', 'description' => 'Site tagline'],
            ['key' => 'support_email', 'value' => 'support@nbhca.org.uk', 'type' => 'string', 'group' => 'general', 'description' => 'Support email address'],
            ['key' => 'support_phone', 'value' => '020 7123 4567', 'type' => 'string', 'group' => 'general', 'description' => 'Support phone number'],
            
            // Maintenance Settings
            ['key' => 'maintenance_mode', 'value' => 'false', 'type' => 'boolean', 'group' => 'maintenance', 'description' => 'Enable maintenance mode'],
            ['key' => 'maintenance_message', 'value' => 'The NBHCA Revenue Engine is currently undergoing scheduled maintenance. Ticket sales will resume shortly.', 'type' => 'string', 'group' => 'maintenance', 'description' => 'Public maintenance message'],
            ['key' => 'maintenance_start', 'value' => null, 'type' => 'string', 'group' => 'maintenance', 'description' => 'Scheduled maintenance start time'],
            ['key' => 'maintenance_end', 'value' => null, 'type' => 'string', 'group' => 'maintenance', 'description' => 'Scheduled maintenance end time'],
            
            // Payment Settings
            ['key' => 'vat_rate', 'value' => '20', 'type' => 'float', 'group' => 'payment', 'description' => 'VAT rate percentage'],
            ['key' => 'currency', 'value' => 'GBP', 'type' => 'string', 'group' => 'payment', 'description' => 'Default currency'],
            ['key' => 'transaction_fee_percentage', 'value' => '2.5', 'type' => 'float', 'group' => 'payment', 'description' => 'Transaction fee percentage'],
            ['key' => 'pass_fees_to_buyer', 'value' => 'true', 'type' => 'boolean', 'group' => 'payment', 'description' => 'Pass transaction fees to buyer'],
            
            // Backup Settings
            ['key' => 'backup_frequency', 'value' => 'daily', 'type' => 'string', 'group' => 'backup', 'description' => 'Backup frequency (daily/weekly)'],
            ['key' => 'backup_retention_days', 'value' => '30', 'type' => 'integer', 'group' => 'backup', 'description' => 'Number of days to retain backups'],
            ['key' => 'backup_time', 'value' => '03:00', 'type' => 'string', 'group' => 'backup', 'description' => 'Preferred backup time'],
            
            // CRM Settings
            ['key' => 'crm_enabled', 'value' => 'false', 'type' => 'boolean', 'group' => 'crm', 'description' => 'Enable CRM integration'],
            ['key' => 'crm_api_url', 'value' => null, 'type' => 'string', 'group' => 'crm', 'description' => 'CRM API URL'],
            ['key' => 'crm_sync_on_checkout', 'value' => 'true', 'type' => 'boolean', 'group' => 'crm', 'description' => 'Sync buyer data to CRM on checkout'],
            ['key' => 'crm_auto_tag_tickets', 'value' => 'true', 'type' => 'boolean', 'group' => 'crm', 'description' => 'Auto-tag tickets in CRM'],
        ];

        foreach ($settings as $setting) {
            SystemSetting::firstOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
