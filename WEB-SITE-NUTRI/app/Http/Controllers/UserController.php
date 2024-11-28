<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Exibir o formulário de login
    public function showLoginForm()
    {
        return view('usuarios.login');
    }

    // Processar o login do usuário
    public function login(Request $request) 
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('web')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'As credenciais não correspondem aos nossos registros.',
            'password' => 'Senha incorreta. Tente novamente.',
        ])->withInput();  // Com .withInput() o usuário mantém os campos preenchidos
    }

    // Exibir o formulário de registro
    public function showRegistroForm()
    {
        return view('usuarios.registro');
    }

    // Processar o registro de um novo usuário
    public function registro(Request $request)
    {
        // Validação dos dados de entrada
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed', // Senha mínima de 8 caracteres e confirmação de senha
        ]);

        // Criar o novo usuário
        $usuario = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Criptografando a senha
        ]);

        // Fazer login automaticamente após o cadastro
        Auth::login($usuario);

        // Redirecionar para o dashboard após o registro
        return redirect('/dashboard');
    }

    // Realizar o logout do usuário
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->regenerateToken();
        $request->session()->invalidate();
        $request->session()->regenerate(); // Não é obrigatório

        return redirect('/');
    }
}
