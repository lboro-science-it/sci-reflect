<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ActivityTest extends TestCase
{
    use DatabaseTransactions;
    
    /**
     * Test Activity isOpen function.
     *
     * @return void
     */
    public function testActivityIsOpen()
    {
        $activity = factory(\App\Activity::class)->create();

        $this->assertTrue($activity->isOpen());

        $now = date('Y-m-d H:i:s');
        $earlier = date('Y-m-d H:i:s', strtotime("$now - 1 day"));
        $later = date('Y-m-d H:i:s', strtotime("$now + 1 day"));

        $activity->open_date = $earlier;
        $activity->close_date = $later;

        $this->assertTrue($activity->isOpen());

        $activity->open_date = $later;

        $this->assertFalse($activity->isOpen());

        $activity->open_date = $earlier;
        $activity->close_date = $earlier;

        $this->assertFalse($activity->isOpen());
    }
}
