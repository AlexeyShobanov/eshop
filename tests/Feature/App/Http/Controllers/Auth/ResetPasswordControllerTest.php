<?php

namespace Tests\Feature\App\Http\Controllers\Auth;

use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Requests\ResetPasswordFormRequest;
use Database\Factories\UserFactory;
use Domain\Auth\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

use function action;
use function bcrypt;
use function route;

class ResetPasswordControllerTest extends TestCase
{
    use RefreshDatabase;

    private string $token;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = UserFactory::new()->create();
        $this->token = Password::createToken($this->user);
    }

    /**
     * @test
     * @return  void
     */
    public function it_reset_password_page_success(): void
    {
        $this->get(action([ResetPasswordController::class, 'page'], $this->token))
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

        $request = ResetPasswordFormRequest::factory()->create([
            'email' => $this->user->email,
            'password' => $password,
            'password_confirmation' => $password,
            'token' => $this->token
        ]);

        $response = $this->post(
            action([ResetPasswordController::class, 'handle']),
            $request
        );

        $userWithNewPass = User::query()
            ->find($this->user->id);


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
        $password = bcrypt('1234567890');

        $this->user->password = $password;

        $this->assertEquals($password, $this->user->password);

        $request = ResetPasswordFormRequest::factory()->create([
            'email' => $this->user->email,
            'password' => '12345',
            'password_confirmation' => '12345',
            'token' => $this->token
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
        $request = ResetPasswordFormRequest::factory()->create([
            'email' => $this->user->email,
            'password' => '12345',
            'token' => $this->token
        ]);
        $response = $this->post(
            action([ResetPasswordController::class, 'handle']),
            $request
        );

        $response->assertInvalid(['password']);
    }
}
