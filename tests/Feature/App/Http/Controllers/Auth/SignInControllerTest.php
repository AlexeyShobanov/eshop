<?php

namespace Tests\Feature\App\Http\Controllers\Auth;

use App\Http\Controllers\Auth\SignInController;
use App\Http\Requests\SignInFormRequest;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use function action;
use function bcrypt;
use function route;

class SignInControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @return  void
     */
    public function it_login_page_success(): void
    {
        $this->get(action([SignInController::class, 'page']))
            ->assertOk()
            ->assertSee('Вход в аккаунт')
            ->assertViewIs('auth.login');
    }

    /**
     * @test
     * @return  void
     */
    public function it_login_handle_success(): void
    {
        $password = '123456789';
        $user = UserFactory::new()->create([
            'email' => 'testing@cutcode.ru',
            'password' => bcrypt($password),
        ]);

        $request = SignInFormRequest::factory()->create([
            'email' => $user->email,
            'password' => $password
        ]);

        $response = $this->post(action([SignInController::class, 'handle']), $request);

        $response->assertValid()
            ->assertRedirect(route('home'));

        $this->assertAuthenticatedAs($user);
    }

    /**
     * @test
     * @return  void
     */
    public function it_login_pass_failed(): void
    {
        $password = '123456789';
        $user = UserFactory::new()->create([
            'email' => 'testing@cutcode.ru',
            'password' => bcrypt($password),
        ]);

        $request = SignInFormRequest::factory()->create([
            'email' => $user->email,
            'password' => '12345'
        ]);

        $response = $this->post(action([SignInController::class, 'handle']), $request);

        $response->assertInvalid(['email']);
    }

    /**
     * @test
     * @return  void
     */
    public function it_login_email_failed(): void
    {
        $password = '123456789';
        $user = UserFactory::new()->create([
            'email' => 'testing@cutcode.ru',
            'password' => bcrypt($password),
        ]);

        $request = SignInFormRequest::factory()->create([
            'email' => 'fail@cutcode.ru',
            'password' => $password
        ]);

        $response = $this->post(action([SignInController::class, 'handle']), $request);

        $response->assertInvalid(['email']);
    }

    /**
     * @test
     * @return  void
     */
    public function it_logout_success(): void
    {
        $user = UserFactory::new()->create([
            'email' => 'testing@cutcode.ru',
        ]);

        $this->actingAs($user) //авторизуемся под созданным пользователем
        ->delete(action([SignInController::class, 'logOut']));

        $this->assertGuest();
    }

    /**
     * @test
     * @return  void
     */
    public function it_logout_guest_middleware_fail(): void
    {
        $this->delete(action([SignInController::class, 'logOut']))
            ->assertRedirect(route('home'));
    }
}
