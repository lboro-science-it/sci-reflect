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
        $colours = [
            '#dff0d8',
            '#d9edf7',
            '#fcf8e3',
            '#f2dede',
            '#ED2482',
            '#361163'
        ];

        for ($i = 1; $i <= 6; $i++) {
            DB::table('categories')->insert([
                'activity_id' => 1,
                'name' => 'Category ' . $i,
                'color' => $colours[$i - 1],
                'icon_href' => ''
            ]);
        }
    }
}
