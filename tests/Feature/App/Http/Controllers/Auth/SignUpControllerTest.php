<?php

namespace Tests\Feature\App\Http\Controllers\Auth;

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
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

use function action;
use function route;

class SignUpControllerTest extends TestCase
{
    use RefreshDatabase;

    // создаем свойвство в котором будет хранить реквест
    protected array $request;

    // метод setUp будет запускаться перед каждым тестом создавая окружение
    protected function setUp(): void
    {
        parent::setUp();

        $this->request = SignUpFormRequest::factory()->create([
            'email' => 'testing@cutcode.ru'
        ]);
    }

    // метод выполняющий запрос к методу handle тестируемого контроллера
    private function request(): TestResponse
    {
        return $this->post(
            action([SignUpController::class, 'handle']), // котроллер и метод к которому обращаемся
            $this->request   // что передаем
        );
    }

    private function findUser(): User
    {
        return User::query()
            ->where('email', $this->request['email'])
            ->first();
    }

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
    public function it_validation_success(): void
    {
        $this->request()
            ->assertValid();
    }

    /**
     * @test
     * @return  void
     */
    public function it_validation_fail_password_confirm(): void
    {
        $this->request['password_confirmation'] = '987654321';

        $this->request()
            ->assertInvalid(['password']);
    }

    /**
     * @test
     * @return  void
     */
    public function it_user_create_success(): void
    {
        $this->assertDatabaseMissing('users', [
            'email' => $this->request['email']
        ]);

        $this->request();

        $this->assertDatabaseHas('users', [
            'email' => $this->request['email']
        ]);
    }

    /**
     * @test
     * @return  void
     */
    public function it_fail_validation_on_unique_email(): void
    {
        UserFactory::new()->create([
            'email' => $this->request['email']
        ]);

        $this->assertDatabaseHas('users', [
            'email' => $this->request['email']
        ]);

        $this->request()
            ->assertInvalid(['email']);
    }

    /**
     * @test
     * @return  void
     */
    public function it_registeres_event_and_listeners_dispatched(): void
    {
        Event::fake();

        $this->request();

        Event::assertDispatched(Registered::class); // проверяем что вызывается событие
//        Event::assertListening(Registered::class, SendEmailNewUserListener::class); //проверяем что вызывается листенер
        Event::assertListening(
            Registered::class,
            SendEmailVerificationNotification::class
        ); //проверяем что вызывается листенер
    }

    /**
     * @test
     * @return  void
     */
    public function it_notification_sent(): void
    {
//      Notification::fake() - лучше сразу вынести в родительский класс TestCase
//        Notification::fake();  //подменяем все Notification на фейк (на уровне фасада)

        $this->request();

        Notification::assertSentTo(
            $this->findUser(),
            VerifyEmail::class
        ); // проверяем что было отправлено уведомление созданному юзеру
    }

    /**
     * @test
     * @return  void
     */
    public function it_user_authenticates_after_and_redirected(): void
    {
        $this->request()
            ->assertRedirect(route('verification.notice'));

        $this->assertAuthenticatedAs(
            $this->findUser()
        ); // проверяем, что пользователь авторизован именно как созданный пользователь
    }
}
