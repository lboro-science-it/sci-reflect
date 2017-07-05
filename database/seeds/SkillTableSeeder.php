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
        $pageId = 4;

        for ($skillId = 1; $skillId <= 4; $skillId++) {
            
            DB::table('skills')->insertGetId([
                'activity_id' => 1,
                'category_id' => $skillId % 2 + 1,
                'title' => 'Skill ' . $skillId,
                'description' => 'Description for Skill ' . $skillId,
                'info_link' => 'https://google.co.uk/search?q=attribute+' . $skillId,
            ]);

            DB::table('page_skill')->insert([
                'skill_id' => $skillId,
                'page_id' => $pageId,
                'position' => $skillId
            ]);
        }
    }
}
