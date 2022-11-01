<?php

namespace Tests\Feature\App\Http\Controllers;

use App\Http\Controllers\AuthController;
use App\Http\Requests\ResetPasswordFormRequest;
use App\Http\Requests\SignInFormRequest;
use App\Http\Requests\SignUpFormRequest;
use App\Listeners\SendEmailNewUserListener;
use App\Models\User;
use App\Notifications\NewUserNotification;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{

    use RefreshDatabase;

    /**
     * @test
     * @return  void
     */
    public function it_login_page_success(): void
    {
        $this->get(action([AuthController::class, 'index']))
            ->assertOk()
            ->assertSee('Вход в аккаунт')
            ->assertViewIs('auth.index');
    }

    /**
     * @test
     * @return  void
     */
    public function it_sign_up_page_success(): void
    {
        $this->get(action([AuthController::class, 'signUp']))
            ->assertOk()
            ->assertSee('Регистрация')
            ->assertViewIs('auth.sign-up');
    }

    /**
     * @test
     * @return  void
     */
    public function it_forgot_page_success(): void
    {
        $this->get(action([AuthController::class, 'forgot']))
            ->assertOk()
            ->assertSee('Забыли пароль')
            ->assertViewIs('auth.forgot-password');
    }

    /**
     * @test
     * @return  void
     */
    public function it_forgot_password_page_success(): void
    {
        $this->get(action([AuthController::class, 'forgot']))
            ->assertOk()
            ->assertSee('Забыли пароль')
            ->assertViewIs('auth.forgot-password');
    }

    /**
     * @test
     * @return  void
     */
    public function it_sign_in_page_success(): void
    {
        $password = '123456789';
        $user = User::factory()->create([
            'email' => 'testing@cutcode.ru',
            'password' => bcrypt($password),
        ]);

        $request = SignInFormRequest::factory()->create([
            'email' => $user->email,
            'password' => $password
        ]);

        $response = $this->post(action([AuthController::class, 'signIn']), $request);

        $response->assertValid()
            ->assertRedirect(route('home'));

        $this->assertAuthenticatedAs($user);
    }

    /**
     * @test
     * @return  void
     */
    public function it_log_out_page_success(): void
    {
        $user = User::factory()->create([
            'email' => 'testing@cutcode.ru',
        ]);

        $this->actingAs($user) //авторизуемся под созданным пользователем
        ->delete(action([AuthController::class, 'logOut']));

        $this->assertGuest();
    }

    /**
     * @test
     * @return  void
     */
    public function it_forgot_password_success(): void
    {
        Notification::fake();  //подменяем все Notification на фейк (на уровне фасада)
        Event::fake();
        $user = User::factory()->create([
            'email' => 'testing@cutcode.ru',
        ]);

        $request = ResetPasswordFormRequest::factory()->create([
            'email' => $user->email,
        ]);

        $this->post(action([AuthController::class, 'forgotPassword']), $request)
            ->assertValid();

        Notification::assertSentTo($user, ResetPassword::class);
    }

    /**
     * @test
     * @return  void
     */
    public function it_reset_page_success(): void
    {
        $token = '12345';

        $this->get(action([AuthController::class, 'reset'], $token))
            ->assertOk()
            ->assertSee('Восстановление пароля')
            ->assertViewIs('auth.reset-password');
    }

    /**
     * @test
     * @return  void
     */
    public function it_reset_password_page_success(): void
    {
        Event::fake();

        $password = bcrypt('1234567890');

        $user = User::factory()->create([
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
            action([AuthController::class, 'resetPassword']),
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
    public function it_store_success(): void
    {
        Notification::fake();  //подменяем все Notification на фейк (на уровне фасада)
        Event::fake();

        $request = SignUpFormRequest::factory()->create([
            'email' => 'testing@cutcode.ru'
        ]);

        $this->assertDatabaseMissing('users', [
            'email' => $request['email']
        ]);

        $response = $this->post(
            action([AuthController::class, 'store']), // котроллер и метод к которому обращаемся
            $request    // что передаем
        );

        $response->assertValid();

        Event::assertDispatched(Registered::class); // проверяем что вызывается событие
        Event::assertListening(Registered::class, SendEmailNewUserListener::class); //проверяем что вызывается листенер

        $this->assertDatabaseHas('users', [
            'email' => $request['email']
        ]);

        $user = User::query()
            ->where('email', $request['email'])
            ->first();
        $event = new Registered($user); //создаем событие вручную
        $listener = new SendEmailNewUserListener(); // создаем слушателя
        $listener->handle($event); // запускаем слушателя на созданное событие
        Notification::assertSentTo(
            $user,
            NewUserNotification::class
        ); // проверяем что было отправлено уведомление созданному юзеру

        $this->assertAuthenticatedAs(
            $user
        ); // проверяем, что пользователь авторизован именно как созданный пользователь

        $response->assertRedirect(route('home'));
    }

}
