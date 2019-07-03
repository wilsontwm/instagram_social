<?php

namespace App\Services;

use App\SocialAccount;
use App\User;
use App\Role;
use Laravel\Socialite\Contracts\User as ProviderUser;

class SocialAccountService
{
    public function createOrGetUser(ProviderUser $providerUser, $provider)
    {
        $account = SocialAccount::whereProvider($provider)
            ->whereProviderUserId($providerUser->getId())
            ->first();

        if ($account) {
            // Update the user detail if there is any update
            InstagramService::getUser($providerUser->getId(), $providerUser->getNickname(), $providerUser->getAvatar(), $providerUser->getName());

            return $account->user;
        } else {

            $account = new SocialAccount([
                'provider_user_id' => $providerUser->getId(),
                'provider' => $provider
            ]);

            $user = User::where('instagram_id', $providerUser->getId())->first();

            if (!$user) {

                $user = User::create([
                    'email' => $providerUser->getEmail(),
                    'username' => $providerUser->getNickname(),
                    'instagram_id' => $providerUser->getId(),
                    'name' => $providerUser->getName(),
                    'password' => bcrypt($this->generateRandomPassword()),
                    'user_pic' => $providerUser->getAvatar()
                ]);
            }

            $account->user()->associate($user);
            $account->save();

            return $user;
        }
    }

    public function generateRandomPassword()
    {
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789!@#$%^&*()_+,./<>?;:[]{}";
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }
}