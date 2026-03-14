<?php

namespace Database\Seeders;

use App\Models\SystemSetting;
use Illuminate\Database\Seeder;

class SystemSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General
            ['key' => 'app_name', 'value' => 'Luminous Jepara', 'type' => 'string', 'group' => 'general', 'description' => 'Application Name'],
            ['key' => 'maintenance_mode', 'value' => 'false', 'type' => 'boolean', 'group' => 'general', 'description' => 'Maintenance Mode'],
            
            // Features
            ['key' => 'enable_wilayah', 'value' => 'true', 'type' => 'boolean', 'group' => 'features', 'description' => 'Enable Kecamatan & Desa Fields'],
            
            // Map
            ['key' => 'map_api_key', 'value' => '', 'type' => 'string', 'group' => 'map', 'description' => 'Google Maps API Key'],
            ['key' => 'map_center_lat', 'value' => '-6.5888', 'type' => 'string', 'group' => 'map', 'description' => 'Default Map Center Latitude'],
            ['key' => 'map_center_lng', 'value' => '110.6684', 'type' => 'string', 'group' => 'map', 'description' => 'Default Map Center Longitude'],
            
            // Backup
            ['key' => 'auto_backup', 'value' => 'false', 'type' => 'boolean', 'group' => 'backup', 'description' => 'Enable Auto Backup'],
            ['key' => 'backup_schedule', 'value' => 'daily', 'type' => 'string', 'group' => 'backup', 'description' => 'Backup Schedule (daily/weekly)'],
            
            // Power
            ['key' => 'electricity_rate', 'value' => '1444', 'type' => 'integer', 'group' => 'power', 'description' => 'Electricity Rate (Rp per kWh)'],
        ];

        foreach ($settings as $setting) {
            SystemSetting::create($setting);
        }
    }
}
