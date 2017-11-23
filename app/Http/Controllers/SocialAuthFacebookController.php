<?php

namespace App\Http\Controllers;

use Socialite;
use App\Services\SocialFacebookAccountService;

class SocialAuthFacebookController extends Controller
{
    /**
     * Create a redirect method to facebook api.
     *
     * @return void
     */
    public function redirect()
    {
        return Socialite::driver('facebook')->redirect();
    }
    /**
     * Return a callback method from facebook api.
     *
     * @return callback URL from facebook
     */
    public function callback(SocialFacebookAccountService $service)
    {
        $user = $service->createOrGetUser(Socialite::driver('facebook')->user());
        auth()->login($user);
        return redirect()->to('/home');
    }

    /**
     * Deauths user after he/she removed app right on Facebook
     *
     * @param SocialFacebookAccountService $service
     */
    public function deauth(SocialFacebookAccountService $service)
    {
        $service->deauthUser(request('signed_request'));
    }
}
