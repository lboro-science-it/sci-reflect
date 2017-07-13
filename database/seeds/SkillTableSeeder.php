<?php

use Illuminate\Database\Seeder;

class SkillTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($skillId = 1; $skillId <= 4; $skillId++) {
            
            DB::table('skills')->insertGetId([
                'activity_id' => 1,
                'category_id' => $skillId % 2 + 1,
                'title' => 'Skill ' . $skillId,
                'description' => 'Description for Skill ' . $skillId,
                'info_link' => 'https://google.co.uk/search?q=attribute+' . $skillId,
                'number' => $skillId,
            ]);

            DB::table('page_skill')->insert([
                'skill_id' => $skillId,
                'page_id' => 4,
                'position' => $skillId
            ]);

            if ($skillId % 2 == 1) {
                DB::table('page_skill')->insert([
                    'skill_id' => $skillId,
                    'page_id' => 5,
                    'position' => $skillId
                ]);
            }
        }
    }
}
