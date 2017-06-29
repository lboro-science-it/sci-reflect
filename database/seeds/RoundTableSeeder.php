<?php

use Illuminate\Database\Seeder;

class RoundTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('rounds')->insert([
            'activity_id' => 1,
            'round_number' => 2,
            'title' => 'Round 2',
        ]);
    }
}
