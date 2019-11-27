<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Auth0IndexController extends Controller
{
    /**
     * Redirect to the Auth0 hosted login page
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function login()
    {
        // 以下のキーを使用してアクセストークンを取得
        // 'audience' => config('laravel-auth0.api_identifier')
        return app()->make('auth0')->login(null, null, [
            'scope' => 'openid profile email',
        ]);
    }

    /**
     * Log out of Auth0
     *
     * @return mixed
     */
    public function logout()
    {
        auth()->logout();
        return redirect()->intended(config('laravel-auth0.logout_url'));
    }
}
