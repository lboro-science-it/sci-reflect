<?php

use Illuminate\Database\Seeder;

class PageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create pages unique to each round + relationships
        for ($roundId = 1; $roundId <= 2; $roundId++) {

            // skip first round intro page as created when admin logs in
            if ($roundId == 1) {
                // intro page
                $pageId = DB::table('pages')->insertGetId([
                    'activity_id' => 1,
                    'title' => 'Intro page',
                ]);
                DB::table('page_round')->insert([
                    'page_id' => $pageId,
                    'round_id' => $roundId,
                    'page_number' => 1,
                ]);
            }

            // outro page
            $pageId = DB::table('pages')->insertGetId([
                'activity_id' => 1,
                'title' => 'Outro page',
            ]);
            DB::table('page_round')->insert([
                'page_id' => $pageId,
                'round_id' => $roundId,
                'page_number' => 3
            ]);
        }

        // create pages common across rounds + relationships
        for ($num = 1; $num <= 1; $num++) {
            $pageId = DB::table('pages')->insertGetId([
                'activity_id' => 1,
                'title' => 'Common page ' . $num,
            ]);
            for ($roundId = 1; $roundId <= 2; $roundId++) {
                DB::table('page_round')->insert([
                    'page_id' => $pageId,
                    'round_id' => $roundId,
                    'page_number' => $num + 1,
                ]);
            }
        }
    }
}
