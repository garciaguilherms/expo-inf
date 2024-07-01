<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use App\Models\Auth\AuthUser;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class GoogleSheetsUserProvider implements UserProvider
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function retrieveById($identifier): ?AuthUser
    {
        $user = $this->userRepository->getUserById($identifier);
        return $this->getAuthenticatableUser($user);
    }

    public function retrieveByToken($identifier, $token): ?AuthUser
    {
        $user = $this->userRepository->getUserById($identifier);

        if ($user && $user['remember_token'] === $token) {
            return $this->getAuthenticatableUser($user);
        }

        return null;
    }

    public function updateRememberToken(Authenticatable $user, $token): void
    {
        $this->userRepository->updateRememberToken($user->getAuthIdentifier(), $token);
    }

    public function retrieveByCredentials(array $credentials): ?AuthUser
    {
        $user = $this->userRepository->getUserByEmail($credentials['email']);
        return $this->getAuthenticatableUser($user);
    }

    public function validateCredentials(Authenticatable $user, array $credentials): bool
    {
        return Hash::check($credentials['password'], $user->getAuthPassword());
    }

    protected function getAuthenticatableUser($user): ?AuthUser
    {
        if (!$user) {
            return null;
        }

        $authUser = new AuthUser();
        $authUser->id = $user['id'];
        $authUser->name = $user['name'];
        $authUser->email = $user['email'];
        $authUser->password = $user['password'];
        $authUser->remember_token = $user['remember_token'] ?? null;

        return $authUser;
    }
}
