<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StudentRouteAuthenticationTest extends TestCase
{
    /**
     * Todo:
     * When a user is authed and has a relationship to a given activity,
     * They cannot access a different activity
     * They cannot access a different student's responses in current activity
     * They cannot post another student's responses in current activity
     * They cannot post another student's responses in a different activity
     * They cannot post another student's selections in a different activity
     * They cannot post another student's selections in current activity
     * They cannot access a round that is not open
     * They cannot access a round given that they haven't completed previous round
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }
}
