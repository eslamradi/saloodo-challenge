<?php

namespace Tests\Feature;

use App\Models\Biker;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Tests\TestCaseWithAcceptJson;
use Illuminate\Support\Str;

class AuthorizationTest extends TestCaseWithAcceptJson
{
    use DatabaseTransactions;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_guards_on_routes()
    {
        $user = Biker::factory()->create();
        $token = Auth::guard($user->role)->login($user);

        $routePart = Str::random(6);
        Route::middleware("auth:$user->role")->get($routePart, function () {
            return response()->json(['success' => true]);
        });

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}"
        ])->get($routePart);
        $response->assertStatus(200);

        $routePart = Str::random(6);
        Route::middleware("auth:" . Role::CUSTOMER)->get($routePart, function () {
            return response()->json(['success' => true]);
        });

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}"
        ])->get($routePart);
        $response->assertStatus(401);
    }
}
