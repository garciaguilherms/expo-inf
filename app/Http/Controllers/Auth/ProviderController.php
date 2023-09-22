<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ProviderController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)
            ->with([
                'hd' => 'inf.ufsm.br'
            ])
            ->redirect();
    }

    public function callback($provider)
    {
        $user = Socialite::driver($provider)->user();

        if (explode("@", $user->getEmail())[1] !== 'inf.ufsm.br') {
            return redirect()->to('/login');
        }

        $user = User::updateOrCreate(
            [
                'provider_id' => $user->id,
                'provider' => $provider,
            ],
            [
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'provider_token' => $user->token
            ]
        );

        Auth::login($user);

        return redirect()->route('dashboard');
    }

}
