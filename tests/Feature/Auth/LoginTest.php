<?php

namespace Feature\Auth;

use App\Modules\Auth\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;


/**
 * Class LoginTest
 * @package Tests\Feature\Auth
 */
class LoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function testLoginSuccess()
    {
        $user = User::factory()->create();
        $response = $this->postJson(route('auth.login', ['email' => $user->email, 'password' => 'Secret123!']));
        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * @return void
     */
    public function testLoginFailWrongCredentials()
    {
        $user = User::factory()->create();
        $response = $this->postJson(route('auth.login', ['email' => $user->email, 'password' => 'BadPassword!#$##333']));
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @dataProvider failDataProvider
     * @param array $data
     * @return void
     */
    public function testLoginFail(array $data)
    {
        $response = $this->postJson(route('auth.login', $data));
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @return array[]
     */
    public static function failDataProvider(): array
    {
        return [
            [['email' => '', 'password' => '']],
            [[]],
        ];
    }

}
