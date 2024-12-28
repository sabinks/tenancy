<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LabelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $labels = [
            ['base_color' => '#007BFF', 'text_color' => '#E0E0E0', 'symbol' => ''],
            ['base_color' => '#28A745', 'text_color' => '#FDFD96', 'symbol' => ''],
            ['base_color' => '#FFC107', 'text_color' => '#5A3E1B', 'symbol' => ''],
            ['base_color' => '#DC3545', 'text_color' => '#FFD1D1', 'symbol' => ''],
            ['base_color' => '#6C757D', 'text_color' => '#B2E4E6', 'symbol' => ''],
            ['base_color' => '#20C997', 'text_color' => '#FFDAB9', 'symbol' => ''],
            ['base_color' => '#6610F2', 'text_color' => '#FFC0CB', 'symbol' => ''],
            ['base_color' => '#17A2B8', 'text_color' => '#001F3F', 'symbol' => ''],
            ['base_color' => '#343A40', 'text_color' => '#D4AF37', 'symbol' => ''],
            ['base_color' => '#FF5733', 'text_color' => '#FFFFFF', 'symbol' => ''],
            ['base_color' => '#C70039', 'text_color' => '#FADADD', 'symbol' => ''],
            ['base_color' => '#900C3F', 'text_color' => '#F5F5DC', 'symbol' => ''],
            ['base_color' => '#581845', 'text_color' => '#E6E6FA', 'symbol' => ''],
            ['base_color' => '#1D3557', 'text_color' => '#A8DADC', 'symbol' => ''],
            ['base_color' => '#457B9D', 'text_color' => '#F1FAEE', 'symbol' => ''],
            ['base_color' => '#E63946', 'text_color' => '#F4A261', 'symbol' => ''],
            ['base_color' => '#F77F00', 'text_color' => '#FFFFFF', 'symbol' => ''],
            ['base_color' => '#FFD700', 'text_color' => '#2F4F4F', 'symbol' => ''],
            ['base_color' => '#006D77', 'text_color' => '#EDF6F9', 'symbol' => ''],
            ['base_color' => '#83C5BE', 'text_color' => '#006D77', 'symbol' => ''],
            ['base_color' => '#FF6F61', 'text_color' => '#FFFFFF', 'symbol' => ''],
            ['base_color' => '#8A2BE2', 'text_color' => '#DDA0DD', 'symbol' => ''],
            ['base_color' => '#4B0082', 'text_color' => '#EE82EE', 'symbol' => ''],
            ['base_color' => '#2C3E50', 'text_color' => '#ECF0F1', 'symbol' => ''],
            ['base_color' => '#E74C3C', 'text_color' => '#FDEDEC', 'symbol' => ''],
            ['base_color' => '#3498DB', 'text_color' => '#ECF0F1', 'symbol' => ''],
            ['base_color' => '#2ECC71', 'text_color' => '#FFFFFF', 'symbol' => ''],
            ['base_color' => '#1ABC9C', 'text_color' => '#F0F8FF', 'symbol' => ''],
            ['base_color' => '#9B59B6', 'text_color' => '#EDEDED', 'symbol' => ''],
            ['base_color' => '#34495E', 'text_color' => '#BDC3C7', 'symbol' => ''],
        ];

        foreach ($labels as $label) {
            $foundLabel  = DB::table('labels')->whereColor($label['base_color'])->first();
            if (!$foundLabel) {
                DB::table('labels')->insert([
                    'base_color' => $label['base_color'],
                    'text_color' => $label['text_color'],
                    'symbol' => $label['symbol'],
                ]);
            }
        }
    }
}
