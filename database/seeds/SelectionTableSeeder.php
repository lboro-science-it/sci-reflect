<?php

use Illuminate\Database\Seeder;

class SelectionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // insert selections for 30 users x 12 skills x 2 indicators x 2 rounds
        for ($userId = 1; $userId <= 30; $userId++) {
            for ($roundId = 1; $roundId <= 2; $roundId++) {
                for ($skillId = 1; $skillId <= 12; $skillId++) {
                    DB::table('selections')->insert([
                        'user_id' => $userId,
                        'round_id' => $roundId,
                        'indicator_id' => $skillId * 2 - 1,
                        'choice_id' => rand(1, 4)
                    ]);
                    
                    DB::table('selections')->insert([
                        'user_id' => $userId,
                        'round_id' => $roundId,
                        'indicator_id' => $skillId * 2,
                        'choice_id' => rand(1, 4)
                    ]);
                }
            }
        }
    }
}
