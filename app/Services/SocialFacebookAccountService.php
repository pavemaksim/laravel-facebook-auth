<?php

namespace App\Services;

use App\SocialFacebookAccount;
use App\User;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Contracts\User as ProviderUser;

class SocialFacebookAccountService
{
    /**
     * Creates or retrieves the user
     * While creating the user we save facebook account data
     *
     * @param ProviderUser $providerUser
     * @return mixed
     */
    public function createOrGetUser(ProviderUser $providerUser)
    {
        $account = SocialFacebookAccount::whereProvider('facebook')
            ->whereProviderUserId($providerUser->getId())
            ->first();
        if ($account) {
            return $account->user;
        } else {
            $account = new SocialFacebookAccount([
                'provider_user_id' => $providerUser->getId(),
                'provider' => 'facebook',
                'token' => $providerUser->token
            ]);
            $user = User::whereEmail($providerUser->getEmail())->first();
            if (!$user) {
                $user = User::create([
                    'email' => $providerUser->getEmail(),
                    'name' => $providerUser->getName(),
                    'avatar' => $providerUser->getAvatar(),
                    'password' => md5(rand(1,10000)),
                ]);
            }
            $account->user()->associate($user);
            $account->save();
            return $user;
        }
    }

    public function deauthUser($signedRequest)
    {
        $data = $this->parseSignedRequest($signedRequest);
        if ($data !== null) {
            $account = SocialFacebookAccount::whereProvider('facebook')
                ->whereProviderUserId($data['user_id'])
                ->first();
            if ($account) {
                $account->user->is_active = false;
                $account->user->save();
            }
        }
    }

    /**
     * Parses the signed request from Facebook
     *
     * @param $signed_request
     * @return mixed|null
     */
    protected function parseSignedRequest($signed_request)
    {
        list($encoded_sig, $payload) = explode('.', $signed_request, 2);

        $secret = env('FACEBOOK_CLIENT_SECRET'); // Use your app secret here

        // decode the data
        $sig = $this->base64UrlDecode($encoded_sig);
        $data = json_decode($this->base64UrlDecode($payload), true);

        // confirm the signature
        $expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);
        if ($sig !== $expected_sig) {
            Log::error('Bad Signed JSON signature!');
            return null;
        }

        return $data;
    }

    /**
     * Decodes a base64 encoded URL
     *
     * @param $input
     * @return bool|string
     */
    protected function base64UrlDecode($input)
    {
        return base64_decode(strtr($input, '-_', '+/'));
    }
}