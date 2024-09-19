<?php

namespace Database\Seeders;

use App\Settings;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $setting = Settings::create([
            'material_min_alert_qty' => NULL, 
            'vat' => NULL
        ]);
    }
}
