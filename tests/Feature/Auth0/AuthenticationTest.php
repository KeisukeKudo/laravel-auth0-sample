<?php

namespace Tests\Feature\Auth0;

use App\User;
use Auth0\Login\Auth0Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    private $config;

    protected function setUp(): void
    {
        parent::setUp();

        $this->config = [
            'domain' => config('laravel-auth0.domain'),
            'client_id' => config('laravel-auth0.client_id'),
            'client_secret' => config('laravel-auth0.client_secret'),
            'redirect_uri' => config('laravel-auth0.redirect_uri'),
        ];
    }

    public function test_Transition_to_the_authentication_page()
    {
        $response = $this->get(route('login'));
        $response->assertStatus(302);

        $target = parse_url($response->headers->get('location'));
        $this->assertEquals($this->config['domain'], $target['host']);

        $query = explode('&', $target['query']);
        $this->assertContains('redirect_uri=' . urlencode($this->config['redirect_uri']), $query);
        $this->assertContains('client_id=' . $this->config['client_id'], $query);
    }

    /**
     * @throws \Auth0\SDK\Exception\CoreException
     */
    public function test_Logout()
    {
        $user = User::create([
            'name' => 'name',
            'email' => 'example@mail.com',
            'password' => 'password'
        ]);

        $this->actingAs($user)->post(route('logout'))
            ->assertStatus(302)
            ->assertRedirect(config('laravel-auth0.logout_url'));

        $this->assertGuest();
    }
}
