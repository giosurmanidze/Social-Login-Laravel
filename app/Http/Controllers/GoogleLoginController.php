<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;

class GoogleLoginController extends Controller
{

    public function redirect($provider): RedirectResponse
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider): RedirectResponse
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return $this->redirectToLogin();
        }

        $user = $this->findOrCreateUser($provider, $socialUser);

        Auth::guard('web')->login($user);

        return redirect(env('FRONTEND_URL'));
    }

    private function redirectToLogin(): RedirectResponse
    {
        return redirect(env('FRONTEND_URL') . '/login');
    }

    private function findOrCreateUser($provider, $socialUser): User
    {
        $user = User::where([
            'provider' => $provider,
            'provider_id' => $socialUser->getId(),
        ])->first();

        if (!$user) {
            $this->validateSocialUser($socialUser);

            $user = User::create([
                'name' => User::generateUserName($socialUser->getName()),
                'username' => User::generateUserName($socialUser->getNickname()),
                'email' => $socialUser->getEmail(),
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
                'email_verified_at' => now(),
            ]);
        }

        return $user;
    }

    private function validateSocialUser($socialUser): void
    {
        $validator = Validator::make(
            ['email' => $socialUser->getEmail()],
            ['email' => ['unique:users,email']],
            ['email.unique' => "Couldn't log in. Maybe you used a different login method?"]
        );

        if ($validator->fails()) {
            $this->redirectToLogin()->withErrors($validator)->send();
            exit;
        }
    }
}
