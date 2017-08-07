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
        $categories = [];

        for ($skillId = 1; $skillId <= 12; $skillId++) {
            $catId = rand(1, 6);
            $categories[$catId] = isset($categories[$catId]) ? $categories[$catId] + 1 : 1;

            DB::table('skills')->insertGetId([
                'activity_id' => 1,
                'category_id' => $catId,
                'title' => 'Skill ' . $skillId,
                'description' => 'Description for Skill ' . $skillId,
                'block_id' => 5,
                'number' => $categories[$catId],
            ]);

            DB::table('page_skill')->insert([
                'skill_id' => $skillId,
                'page_id' => 5,
                'position' => rand(1, 12)
            ]);

            DB::table('page_skill')->insert([
                'skill_id' => $skillId,
                'page_id' => 4,
                'position' => rand(1, 12)
            ]);
        }
    }
}
