<?php

namespace Tests\Feature\App\Http\Controllers\Auth;

use App\Http\Controllers\Auth\VerificationEmail;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use function action;
use function route;

class VerificationEmailTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @return  void
     */
    public function it_verified_page_success(): void
    {
        $user = UserFactory::new()->create([
            'email' => 'testing@cutcode.ru',
        ]);
        $this->actingAs($user);

        $this->get(action([VerificationEmail::class, 'page']))
            ->assertOk()
            ->assertSee('Подтвердите e-mail')
            ->assertViewIs('auth.verify-email');
    }

    /**
     * @test
     * @return  void
     */
    public function it_verified_page_guest_failed(): void
    {
        $this->get(action([VerificationEmail::class, 'page']))
            ->assertRedirect(route('login'));
    }
}
