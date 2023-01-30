<?php

namespace Tests\Feature;

use App\Models\Biker;
use App\Models\Customer;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use Tests\TestCaseWithAcceptJson;

class AuthenticationTest extends TestCaseWithAcceptJson
{
    use DatabaseTransactions;

    public function test_register_validation()
    {
        $response = $this->post("/api/customer/register", [
            'name' => '',
            'email' => '',
            'password' => ''
        ]);

        $response->assertStatus(422);
        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->has('errors.password')
                ->has('errors.email')
                ->has('errors.name')
                ->etc()
        );

        $response = $this->post("/api/customer/register", [
            'name' => 'user',
            'email' => 'user-fake-kjk@mail.com',
            'password' => '1234'
        ]);

        $response->assertStatus(422);
        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->has('errors.password')
                ->etc()
        );
    }

    public function test_register_successful()
    {
        $role  = Role::BIKER;
        $response = $this->post("/api/{$role}/register", [
            'name' => 'user',
            'email' => 'user-fake-biker@mail.com',
            'password' => '123456',
        ]);

        $response->assertStatus(200);
        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->has('data.authorization.token')
                ->has('data.user')
                ->etc()
            // check user is registered as a customer
        )->assertJsonPath('data.user.role', $role);

        $role  = Role::CUSTOMER;
        $response = $this->post("/api/{$role}/register", [
            'name' => 'user',
            'email' => 'user-fake-customer@mail.com',
            'password' => '123456'
        ]);

        $response->assertStatus(200);
        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->has('data.authorization.token')
                ->has('data.user')
                ->etc()
            // check user is registered as a customer
        )->assertJsonPath('data.user.role', $role);
    }

    public function test_login_successful_with_role_checked()
    {
        $email = 'test-customer-login@mail.com';
        $password = 'password';

        $user = Customer::factory()->create([
            'email' => $email,
            'password' => Hash::make($password)
        ]);

        $response = $this->post("/api/{$user->role}/login", [
            'email' => $email,
            'password' => $password,
        ]);

        $response->assertStatus(200);
        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->has('data.authorization.token')->etc()
        )->assertJsonPath('data.user.role', $user->role);

        $email = 'test-biker-login@mail.com';
        $password = 'password';

        $user = Biker::factory()->create([
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $response = $this->post("/api/{$user->role}/login", [
            'email' => $email,
            'password' => $password,
        ]);

        $response->assertStatus(200);
        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->has('data.authorization.token')->etc()
        )->assertJsonPath('data.user.role', $user->role);
    }

    public function test_login_fail()
    {
        $email = 'testuserlogin@mail.com';
        $password = 'correctpassword';

        $user = Customer::factory()->create([
            'email' => $email,
            'password' => Hash::make($password)
        ]);

        $response = $this->post("/api/{$user->role}/login", [
            'email' => $email,
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(400);


        $email = 'correct_mail@mail.com';
        $password = 'correctpassword';

        $user = Customer::factory()->create([
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $response = $this->post("/api/{$user->role}/login", [
            'email' => 'wrong_mail@mail.com',
            'password' => $password,
        ]);

        $response->assertStatus(400);
    }

    public function test_logout()
    {
        $user = Customer::factory()->create();
        $token = Auth::guard($user->role)->login($user);

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}"
        ])->post("/api/$user->role/logout");

        $response->assertStatus(200);

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}"
        ])->get("/api/$user->role/me");

        $response->assertStatus(401);
    }

    public function test_refresh_token()
    {
        $user = Customer::factory()->create();
        $token = Auth::guard($user->role)->login($user);

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}"
        ])->post("/api/$user->role/refresh");

        $response->assertStatus(200);
        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->has('data.authorization.token')->has('data.user')->etc()
        );


        $responseContent = $response->json();

        $refreshToken = $responseContent['data']['authorization']['token'];

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$refreshToken}"
        ])->get("/api/$user->role/me");

        $response->assertStatus(200);
    }
}
