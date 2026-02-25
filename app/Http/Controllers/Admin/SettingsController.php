<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = [
            'general' => SystemSetting::getByGroup('general'),
            'payment' => SystemSetting::getByGroup('payment'),
            'maintenance' => SystemSetting::getByGroup('maintenance'),
            'backup' => SystemSetting::getByGroup('backup'),
            'crm' => SystemSetting::getByGroup('crm'),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'settings' => 'required|array',
        ]);

        foreach ($validated['settings'] as $key => $value) {
            SystemSetting::set($key, $value);
        }

        AuditLog::log('settings.updated', null, null, $validated['settings'], 'medium');

        return back()->with('success', 'Settings updated successfully.');
    }

    public function maintenance()
    {
        $settings = SystemSetting::getByGroup('maintenance');
        return view('admin.settings.maintenance', compact('settings'));
    }

    public function toggleMaintenance(Request $request)
    {
        $enabled = $request->boolean('enabled');
        
        SystemSetting::set('maintenance_mode', $enabled, 'boolean', 'maintenance');
        
        if ($request->filled('message')) {
            SystemSetting::set('maintenance_message', $request->message, 'string', 'maintenance');
        }

        AuditLog::log(
            $enabled ? 'maintenance.enabled' : 'maintenance.disabled',
            null,
            null,
            ['enabled' => $enabled],
            'high'
        );

        return back()->with('success', $enabled ? 'Maintenance mode enabled.' : 'Maintenance mode disabled.');
    }

    public function crm()
    {
        $settings = SystemSetting::getByGroup('crm');
        return view('admin.settings.crm', compact('settings'));
    }

    public function updateCrm(Request $request)
    {
        $validated = $request->validate([
            'crm_enabled' => 'boolean',
            'crm_api_url' => 'nullable|url',
            'crm_sync_on_checkout' => 'boolean',
            'crm_auto_tag_tickets' => 'boolean',
        ]);

        foreach ($validated as $key => $value) {
            $type = is_bool($value) ? 'boolean' : 'string';
            SystemSetting::set($key, $value, $type, 'crm');
        }

        return back()->with('success', 'CRM settings updated.');
    }
}
