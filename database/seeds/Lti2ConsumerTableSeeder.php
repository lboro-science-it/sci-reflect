<?php

use Illuminate\Database\Seeder;

class Lti2ConsumerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('lti2_consumer')->insert([
            'name' => 'testLTI',
            'consumer_key256' => 'consumerKey',
            'secret' => 'sharedSecret',
            'enabled' => 1,
        ]);
    }
}
