<?php

namespace App\Http\Controllers;

use App\Models\Consulta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConsultaController extends Controller
{
    // Método para listar as consultas
    public function index()
    {
        if (Auth::user()->tipo_usuario === 'administrador') {
            // Administradores veem todas as consultas
            $consultas = Consulta::all();
        } else {
            // Usuários veem apenas suas próprias consultas
            $consultas = Consulta::where('user_id', auth()->id())->get();
        }

        return view('usuarios.consultas', compact('consultas'));
    }

    // Método para exibir o formulário de criação de consulta
    public function create()
    {
        return view('usuarios.create');
    }

    // Método para armazenar uma nova consulta
    public function store(Request $request)
    {
        // Validação dos dados
        $request->validate([
            'paciente_nome' => 'required|string|max:255',
            'data' => 'required|date',
            'hora' => 'required|date_format:H:i',
            'status' => 'required|string|max:255',
        ]);

        // Verifica se já existe uma consulta no mesmo horário e data
        $consultaExistente = Consulta::where('data', $request->data)
            ->where('hora', $request->hora)
            ->first();

        if ($consultaExistente) {
            // Retorna erro se o horário já estiver ocupado
            return redirect()->back()->with('error', 'Não foi possível agendar a consulta. O horário já está ocupado.');
        }

        // Cria a consulta associada ao usuário autenticado
        Consulta::create([
            'user_id' => auth()->id(),
            'paciente_nome' => $request->paciente_nome,
            'data' => $request->data,
            'hora' => $request->hora,
            'status' => $request->status,
        ]);

        return redirect()->route('consultas.index')->with('success', 'Consulta criada com sucesso!');
    }

    // Método para exibir o formulário de edição de consulta
    public function edit($id)
    {
        $consulta = Consulta::findOrFail($id);

        // Verifica se o usuário tem permissão para editar
        if (Auth::user()->tipo_usuario !== 'administrador' && $consulta->user_id !== Auth::id()) {
            return redirect()->route('consultas.index')->with('error', 'Você não tem permissão para editar esta consulta.');
        }

        return view('usuarios.edit', compact('consulta'));
    }

    // Método para atualizar a consulta
    public function update(Request $request, $id)
    {
        $request->validate([
            'paciente_nome' => 'required|string|max:255',
            'data' => 'required|date',
            'hora' => 'required|date_format:H:i',
            'status' => 'required|string|max:255',
        ]);

        $consulta = Consulta::findOrFail($id);

        // Verifica se o usuário tem permissão para atualizar
        if (Auth::user()->tipo_usuario !== 'administrador' && $consulta->user_id !== Auth::id()) {
            return redirect()->route('consultas.index')->with('error', 'Você não tem permissão para editar esta consulta.');
        }

        // Verifica se a data e hora que está tentando atualizar já está ocupada
        $consultaExistente = Consulta::where('data', $request->data)
            ->where('hora', $request->hora)
            ->where('id', '!=', $id)
            ->first();

        if ($consultaExistente) {
            return redirect()->back()->with('error', 'Não foi possível agendar a consulta. O horário já está ocupado.');
        }

        // Atualiza os dados da consulta
        $consulta->update($request->all());

        return redirect()->route('consultas.index')->with('success', 'Consulta atualizada com sucesso!');
    }

    // Método para deletar a consulta
    public function destroy($id)
    {
        // Verifica se o usuário é administrador
        if (Auth::user()->tipo_usuario !== 'administrador') {
            return redirect()->route('consultas.index')->with('error', 'Você não tem permissão para deletar consultas.');
        }

        $consulta = Consulta::findOrFail($id);

        // Deleta a consulta
        $consulta->delete();

        return redirect()->route('consultas.index')->with('success', 'Consulta deletada com sucesso!');
    }
}
