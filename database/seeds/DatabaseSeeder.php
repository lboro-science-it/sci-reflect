<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (App::environment('local')) {
            $this->call(Lti2ConsumerTableSeeder::class);
            $this->call(RoundTableSeeder::class);
            $this->call(PageTableSeeder::class);
            $this->call(SkillTableSeeder::class);
            $this->call(BlockTableSeeder::class);
            $this->call(IndicatorTableSeeder::class);
            $this->call(ChoiceTableSeeder::class);
            $this->call(CategoryTableSeeder::class);
        }
    }
}
