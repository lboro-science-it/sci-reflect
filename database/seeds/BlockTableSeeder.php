<?php

use Illuminate\Database\Seeder;

class BlockTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $blockId = DB::table('blocks')->insertGetId([
            'activity_id' => 1,
            'content' => "<h2>Welcome!</h2><p>You're about to be shown a series of statements...</p><h3>Guidelines</h3><p>etc...</p>",
        ]);

        DB::table('block_page')->insert([
            'block_id' => $blockId,
            'page_id' => 1,
            'position' => 1,
        ]);

        $blockId = DB::table('blocks')->insertGetId([
            'activity_id' => 1,
            'content' => "<h2>Welcome!</h2><p>You're about to be shown a series of statements...</p><h3>Guidelines</h3><p>etc...</p>",
        ]);

        DB::table('block_page')->insert([
            'block_id' => $blockId,
            'page_id' => 6,
            'position' => 1,
        ]);

        $blockId = DB::table('blocks')->insertGetId([
            'activity_id' => 1,
            'content' => "<h2>Goodbye!</h2><p>You've now finished this round...</p>",
        ]);

        DB::table('block_page')->insert([
            'block_id' => $blockId,
            'page_id' => 2,
            'position' => 1,
        ]);

        $blockId = DB::table('blocks')->insertGetId([
            'activity_id' => 1,
            'content' => "<h2>Goodbye!</h2><p>You've now finished this round...</p>",
        ]);

        DB::table('block_page')->insert([
            'block_id' => $blockId,
            'page_id' => 3,
            'position' => 1,
        ]);
    }
}
