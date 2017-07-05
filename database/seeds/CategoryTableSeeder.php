<?php

use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert([
            'activity_id' => 1,
            'name' => 'Category 1',
            'color' => 'dff0d8',
            'icon_href' => ''
        ]);

        DB::table('categories')->insert([
            'activity_id' => 1,
            'name' => 'Category 2',
            'color' => 'd9edf7',
            'icon_href' => ''
        ]);
    }
}
