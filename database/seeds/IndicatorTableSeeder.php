<?php

use Illuminate\Database\Seeder;

class IndicatorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($skillId = 1; $skillId <= 2; $skillId++) {
            for ($ind = 1; $ind <= 2; $ind++) {
                DB::table('indicators')->insert([
                    'skill_id' => $skillId,
                    'text' => 'Indicator ' . $ind . ' for Attribute ' . $skillId,
                ]);
            }
        }
    }
}
