<?php

namespace Tests\Feature\App\Http\Controllers;

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Requests\ResetPasswordFormRequest;
use Database\Factories\UserFactory;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ForgotPasswordControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @return  void
     */
    public function it_forgot_password_page_success(): void
    {
        $this->get(action([ForgotPasswordController::class, 'page']))
            ->assertOk()
            ->assertSee('Забыли пароль')
            ->assertViewIs('auth.forgot-password');
    }

    /**
     * @test
     * @return  void
     */
    public function it_forgot_password_handle_success(): void
    {
        Notification::fake();  //подменяем все Notification на фейк (на уровне фасада)
        Event::fake();
        $user = UserFactory::new()->create([
            'email' => 'testing@cutcode.ru',
        ]);

        $request = ResetPasswordFormRequest::factory()->create([
            'email' => $user->email,
        ]);

        $this->post(action([ForgotPasswordController::class, 'handle']), $request)
            ->assertValid();

        Notification::assertSentTo($user, ResetPassword::class);
    }

    /**
     * @test
     * @return  void
     */
    public function it_forgot_password_handle_failure(): void
    {
        Notification::fake();  //подменяем все Notification на фейк (на уровне фасада)
        Event::fake();
        UserFactory::new()->create([
            'email' => 'testing@cutcode.ru',
        ]);

        $request = ResetPasswordFormRequest::factory()->create([
            'email' => 'fail.com',
        ]);

        $this->post(action([ForgotPasswordController::class, 'handle']), $request)
            ->assertInvalid();
    }
}
