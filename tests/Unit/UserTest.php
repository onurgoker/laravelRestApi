<?php

namespace Tests\Unit;

use App\User;
use Illuminate\Support\Str;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function test_create_user(): void
    {
        $response = $this->post('/api/register',
            [
                'name' => 'Onur',
                'email' => rand(10000,1000000000) . '@test '. rand(10000,10000000) .'.com',
                'password' => 'temp123'
            ]);
        $response->assertStatus(200);
    }

    public function test_create_user_validation_fail(): void
    {
        $response = $this->post('/api/register',
            ['name' => 'Onur', 'email' => 'test1.com']);
        $response->assertStatus(422);
    }

    public function test_login(): void
    {
        $user = \App\Models\User::orderBy('id', 'DESC')->first();
        $response = $this->post('/api/login',
            ['email' => $user->email, 'password' => 'temp123']);
        $response->assertStatus(200);
    }
}
