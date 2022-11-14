<?php

namespace Tests\Feature\App\Http\Controllers\Auth;

use Tests\TestCase;


class SocialAuthControllerTest extends TestCase
{
//    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
