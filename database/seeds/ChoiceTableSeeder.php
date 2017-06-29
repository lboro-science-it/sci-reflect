<?php

use Illuminate\Database\Seeder;

class ChoiceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($choice = 1; $choice <= 4; $choice++) {
            DB::table('choices')->insert([
                'activity_id' => 1,
                'value' => $choice,
                'label' => 'Choice ' . $choice,
            ]);
        }
    }
}
