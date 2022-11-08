<?php

namespace Tests\Feature\App\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VerificationEmailTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @return  void
     */
    public function it_verified_page_success(): void
    {
//        $this->get(action([VerificationEmail::class, 'page']))
//            ->assertOk()
//            ->assertSee('Подтвердите e-mail')
//            ->assertViewIs('auth.verify-email');
    }
}
