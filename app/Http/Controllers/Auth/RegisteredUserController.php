<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'created_at' => 'date',
        ]);

        // Verifica se o email já existe na planilha
        $existingUser = $this->userRepository->getUserByEmail($request->email);
        if ($existingUser !== null) {
            return redirect()->back()->withInput()->withErrors(['email' => 'Este email já está registrado.']);
        }

        // Gerar um ID único (exemplo simples usando uniqid())
        $id = uniqid();

        // Criar o usuário na planilha Google
        $userData = [
            'id' => $id,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $this->userRepository->createUser($userData);

        // Manualmente criar um objeto User para autenticação
        $user = (object) [
            'id' => $id,
            'name' => $request->name,
            'email' => $request->email,
            'password' => $userData['password'],
            'created_at' => $userData['created_at'],
        ];

        event(new Registered($user));

        // Login manual do usuário
        Auth::loginUsingId($id);

        return redirect(RouteServiceProvider::HOME);
    }

    /**
     * Get the user ID by email from the Google Sheets.
     */
    private function getUserIdByEmail($email)
    {
        $users = $this->userRepository->getAllUsers();
        foreach ($users as $user) {
            if ($user['email'] === $email) {
                return $user['id'];
            }
        }

        return null;
    }
}
