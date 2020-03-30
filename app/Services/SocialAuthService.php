<?php
namespace App\Services;
use App\SocialLogin;
use App\User;
use Laravel\Socialite\Contracts\User as ProviderUser;
class SocialAuthService{
    public function createOrGetUser(ProviderUser $providerUser){
        $account = SocialLogin::where('facebook')->whereProviderUesr($providerUser->getId())
            ->first();
        if($account){
            return $account->user;
        }else{
            $account = new SocialLogin([
                'provider_user_id'=>$providerUser->getId(),
                'provider'=>'facebook',
            ]);
            $user = User::whereEmail($providerUser->getEmail())->first();
            if(!$user){
                $user = User::create([
                    'email'=>$providerUser->getEmail(),
                    'name'=>$providerUser->getName(),
                    'password'=>md5(rand(1,9999)),
                ]);
            }
            $account->user()->associate($user);
            $account->save();
            return $user;

        }
    }

}