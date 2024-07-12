<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;


class ProviderController extends Controller
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function redirect($provider)
    {
        return Socialite::driver($provider)
            ->redirect();
    }

    public function callback($provider): \Illuminate\Http\RedirectResponse
    {
        $socialUser = Socialite::driver($provider)->user();

        $existingUser = $this->userRepository->getUserByEmail($socialUser->getEmail());
        if ($existingUser !== null) {
            $user = $existingUser;
        } else {
            $id = uniqid();

            $userData = [
                'id' => $id,
                'name' => $socialUser->getName(),
                'email' => $socialUser->getEmail(),
                'password' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'provider_id' => $socialUser->getId(),
                'provider' => $provider,
                'provider_token' => $socialUser->token,
            ];

            $this->userRepository->createUser($userData);

            $user = (object) $userData;
        }

        Auth::loginUsingId($user['id']);

        return redirect()->route('dashboard');
    }
}
