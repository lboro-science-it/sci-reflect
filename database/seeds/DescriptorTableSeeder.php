<?php

use Illuminate\Database\Seeder;

class DescriptorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($skill = 1; $skill <= 12; $skill++) {
            for ($choice = 1; $choice <= 4; $choice++) {
                DB::table('descriptors')->insert([
                    'choice_id' => $choice,
                    'skill_id' => $skill,
                    'text' => "This is the descriptor for skill $skill and choice $choice.",
                ]);
            }
        }
    }
}
