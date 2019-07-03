<?php

namespace App\Http\Controllers\Auth;

use App\Services\SocialAccountService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback(SocialAccountService $service, $provider)
    {
        $providerUser = Socialite::driver($provider)->user();

        $user = $service->createOrGetUser($providerUser, $provider);

        auth()->login($user);
        // Set the access token to session
        session()->put('access_token', $providerUser->accessTokenResponseBody['access_token']);
        return redirect()->intended('/home');
    }
}