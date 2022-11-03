<?php

namespace Tests\Feature\App\Http\Controllers;

use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Requests\ResetPasswordFormRequest;
use Database\Factories\UserFactory;
use Domain\Auth\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class ResetPasswordControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @return  void
     */
    public function it_reset_password_page_success(): void
    {
        $token = '12345';

        $this->get(action([ResetPasswordController::class, 'page'], $token))
            ->assertOk()
            ->assertSee('Восстановление пароля')
            ->assertViewIs('auth.reset-password');
    }

    /**
     * @test
     * @return  void
     */
    public function it_reset_password_handle_success(): void
    {
        Event::fake();

        $password = bcrypt('1234567890');

        $user = UserFactory::new()->create([
            'email' => 'testing@cutcode.ru',
            'password' => $password,
        ]);

        $this->assertEquals($password, $user->password);

        $token = Password::broker()->createToken($user);

        $request = ResetPasswordFormRequest::factory()->create([
            'email' => $user->email,
            'token' => $token
        ]);
        $response = $this->post(
            action([ResetPasswordController::class, 'handle']),
            $request
        );

        $userWithNewPass = User::query()
            ->find($user->id);


        $this->assertNotEquals($password, $userWithNewPass->password);

        $response->assertValid()
            ->assertRedirect(route('login'));

        Event::assertDispatched(PasswordReset::class);
    }

    /**
     * @test
     * @return  void
     */
    public function it_reset_password_handle_valid_pass_failed(): void
    {
        Event::fake();

        $password = bcrypt('1234567890');

        $user = UserFactory::new()->create([
            'email' => 'testing@cutcode.ru',
            'password' => $password,
        ]);

        $this->assertEquals($password, $user->password);

        $token = Password::broker()->createToken($user);

        $request = ResetPasswordFormRequest::factory()->create([
            'email' => $user->email,
            'password' => '12345',
            'password_confirmation' => '12345',
            'token' => $token
        ]);
        $response = $this->post(
            action([ResetPasswordController::class, 'handle']),
            $request
        );

        $response->assertInvalid();
    }

    /**
     * @test
     * @return  void
     */
    public function it_reset_password_handle_valid_confirm_pass_failed(): void
    {
        Event::fake();

        $password = bcrypt('1234567890');

        $user = UserFactory::new()->create([
            'email' => 'testing@cutcode.ru',
            'password' => $password,
        ]);

        $this->assertEquals($password, $user->password);

        $token = Password::broker()->createToken($user);

        $request = ResetPasswordFormRequest::factory()->create([
            'email' => $user->email,
            'password' => '12345',
            'token' => $token
        ]);
        $response = $this->post(
            action([ResetPasswordController::class, 'handle']),
            $request
        );

        $response->assertInvalid();
    }
}
