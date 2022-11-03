<?php

namespace Tests\Feature\App\Http\Controllers;

use App\Http\Controllers\Auth\SignUpController;
use App\Http\Requests\SignUpFormRequest;
use Database\Factories\UserFactory;
use Domain\Auth\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class SignUpControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @return  void
     */
    public function it_sign_up_page_success(): void
    {
        $this->get(action([SignUpController::class, 'page']))
            ->assertOk()
            ->assertSee('Регистрация')
            ->assertViewIs('auth.sign-up');
    }

    /**
     * @test
     * @return  void
     */
    public function it_sign_up_handle_success(): void
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
            action([SignUpController::class, 'handle']), // котроллер и метод к которому обращаемся
            $request    // что передаем
        );

        $response->assertValid();

        Event::assertDispatched(Registered::class); // проверяем что вызывается событие
//        Event::assertListening(Registered::class, SendEmailNewUserListener::class); //проверяем что вызывается листенер
        Event::assertListening(
            Registered::class,
            SendEmailVerificationNotification::class
        ); //проверяем что вызывается листенер

        $this->assertDatabaseHas('users', [
            'email' => $request['email']
        ]);

        $user = User::query()
            ->where('email', $request['email'])
            ->first();
        $event = new Registered($user); //создаем событие вручную
        $listener = new SendEmailVerificationNotification(); // создаем слушателя
        $listener->handle($event); // запускаем слушателя на созданное событие
        Notification::assertSentTo(
            $user,
            VerifyEmail::class
        ); // проверяем что было отправлено уведомление созданному юзеру

//        если делать не верификацию, а отправлять письмо с приветствием, то то, что нижу
//        $listener = new SendEmailNewUserListener(); // создаем слушателя
//        $listener->handle($event); // запускаем слушателя на созданное событие
//        Notification::assertSentTo(
//            $user,
//            NewUserNotification::class
//        ); // проверяем что было отправлено уведомление созданному юзеру


        $this->assertAuthenticatedAs(
            $user
        ); // проверяем, что пользователь авторизован именно как созданный пользователь

        $response->assertRedirect(route('verification.notice'));
    }

    /**
     * @test
     * @return  void
     */
    public function it_sign_up_handle_fail(): void
    {
        Notification::fake();  //подменяем все Notification на фейк (на уровне фасада)
        Event::fake();

        UserFactory::new()->create([
            'email' => 'testing@cutcode.ru',
            'password' => bcrypt('1234567890'),
        ]);

        $request = SignUpFormRequest::factory()->create([
            'email' => 'testing@cutcode.ru'
        ]);

        $response = $this->post(
            action([SignUpController::class, 'handle']), // котроллер и метод к которому обращаемся
            $request    // что передаем
        );
        $response->assertValid();
        Event::assertNotDispatched(Registered::class);
        $this->assertGuest();
    }
}
