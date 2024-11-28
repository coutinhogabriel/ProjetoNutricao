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
    public function login(Request $request) //classe request se conecta no navegador, recebe e armazena, efetuando uma validação das informações 
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // no if abaixo é para efetuar a validação dentro do banco de dados com os registros cadastrados
        if (Auth::guard('web')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }


        return back()->withErrors([
            'email' => 'As credenciais não correspondem aos nossos registros.',
        ])->onlyInput('email');
    }


    // Exibir o formulário de registro
    public function showRegistroForm()
    {
        return view('usuarios.registro'); //informar rota das páginas para que no web.php (routes), quando clicarmos no botão de registro puxar as informações
    }


    // Processar o registro de um novo usuário
    public function registro(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);


        $usuario = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);


        Auth::login($usuario); //cadastra o usuário


        return redirect('/dashboard');//após o cadastro já redireciona ele para a pagina interna
    }


    // Realizar o logout do usuário
    public function logout(Request $request)
    {
        Auth::logout();


        $request->session()->regenerateToken();
        $request->session()->invalidate();
        $request->session()->regenerate(); //não é obrigatório


        return redirect('/');
    }
}