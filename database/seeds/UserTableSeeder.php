<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create 30 users
        for ($i = 1; $i < 31; $i++) {
            $userId = DB::table('users')->insertGetId([
                'name' => 'Test User ' . $i,
                'email' => 'test' . $i . '@test.com',
                'password' => null,
                'remember_token' => null
            ]);

            DB::table('activity_user')->insert([
                'activity_id' => 1,
                'user_id' => $userId,
                'role' => 'student',
                'lti_user_id' => 1000 + $i,     // as these dummy users won't actually be linked to an lti consumer, we just give them high ids
                'current_page' => null,
                'current_round' => null,
                'complete' => 1,
                'group_id' => null
            ]);
        }
    }
}
