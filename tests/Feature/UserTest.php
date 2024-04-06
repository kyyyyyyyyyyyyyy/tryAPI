<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    public function testRegisterSuccess()
    {
        $this->post('/api/users', [
            'username' => 'kyuu',
            'password' => 'password',
            'name' => 'kyky'
        ])->assertStatus(201)
            ->assertJson([
                'data' => [
                    'username' => 'kyuu',
                    'name' => 'kyky'
                ]
            ]);
    }


    public function testRegisterFailed()
    {
        $this->post('/api/users', [
            'username' => '',
            'password' => '',
            'name' => ''
        ])->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'username' => [
                        'The username field is required.'
                    ],
                    'password' => [
                        'The password field is required.'
                    ],
                    'name' => [
                        'The name field is required.'
                    ]
                ]
            ]);
    }

    public function testRegisterUsernameAlreadyExists()
    {
        $this->testRegisterSuccess();
        $this->post('/api/users', [
            'username' => 'kyuu',
            'password' => 'password',
            'name' => 'kyky'
        ])->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'username' => [

                    ]
                ]
            ]);
    }


    public function testLoginSuccess()
    {
        $this->seed([UserSeeder::class]);
        $this->post('/api/users/login', [
            'username' => 'test',
            'password' => 'test',
        ])->assertStatus(200)
            ->assertJson([
                'data' => [
                    'username' => 'test',
                    'name' => 'test'
                ]
            ]);

        $user = User::where('username', 'test')->first();
        self::assertNotNull($user->token);
    }

    public function testLoginFailedUsernameOrPasswordNotFound()
    {
        $this->seed([UserSeeder::class]);
        $this->post('/api/users/login', [
            'username' => 'test',
            'password' => 'salah',
        ])->assertStatus(401)
            ->assertJson([
                'errors' => [
                    'message' => ['username or password wrong']
                ]
            ]);
    }

    public function testGetSuccess()
    {
        $this->seed(UserSeeder::class);

        $this->get('/api/users/current/', [
            'Authorization' => 'test'
        ])->assertStatus(200)
            ->assertJson([
                'data' => [
                    'username' => 'test',
                    'name' => 'test'
                ]
            ]);
    }

    public function testGetUnauthorized()
    {
        $this->seed(UserSeeder::class);

        $this->get('/api/users/current/')
        ->assertStatus(401)
            ->assertJson([
                'errors' => [
                    'message' => []
                ]
            ]);
    }

    public function testGetInvalidToken()
    {
        $this->seed(UserSeeder::class);

        $this->get('/api/users/current/', [
            'Authorization' => 'salah'
        ])
        ->assertStatus(401)
            ->assertJson([
                'errors' => [
                    'message' => []
                ]
            ]);
    }

}
