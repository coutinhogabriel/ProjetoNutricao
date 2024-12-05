@extends('layouts.app')

@section('content')
<header>
        <div class="logo-container">
            <img src="{{ asset('imgs/logo.jpg') }}" alt="" class="logo-img">
            <div class="logo-text">Clínica Bem Viver</div>
        </div>
        <!-- Exibe o nome do usuário abaixo do logo -->
        @if (Auth::check())
            <div class="user-greeting">
                <h3>Olá, {{ Auth::user()->name }}</h3>
                <h4>{{ Auth::user()->tipo_usuario }}</h4>
            </div>
        @endif
        <nav>
            <a href="sobre-nos">Sobre Nós</a>
            <!-- Dropdown para Serviços -->
            <div class="dropdown">
                <a href="#servicos">Serviços</a>
                <div class="dropdown-content">
                    <!-- Link de Agendamento Online modificado -->
                    <a href="{{ route('agendamento.index') }}">Agendamento Online</a>
                    <a href="#medicos">Nossa Equipe</a>
                    <a href="#planos">Planos de Atendimento</a>
                    <a href="#avaliacao">Avaliações Nutricionais</a>
                    <!-- Link para Minhas Consultas -->
                    <a href="{{ route('consultas.index') }}">Minhas Consultas</a>
                </div>
            </div>
            <!-- Sempre exibe o botão de Logout se o usuário estiver autenticado -->
            @if (Auth::check())
                <form action="/logout" method="post">
                    @csrf
                    <button type="submit" class="login-button">Logout</button>
                </form>
            @endif
        </nav>
    </header>

<div class="container">
    <h1 class="my-4">Minhas Consultas</h1>

    <!-- Exibe mensagem de sucesso ou erro -->
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    @if ($message = Session::get('error'))
        <div class="alert alert-danger">
            <p>{{ $message }}</p>
        </div>
    @endif

    <!-- Botão para criar nova consulta -->
    <a class="btn btn-success mb-3" href="{{ route('consultas.create') }}">Inserir Nova Consulta</a>

    <!-- Verifica se há consultas e exibe -->
    @if ($consultas->isNotEmpty())
        <!-- Tabela de Consultas -->
        <table class="table table-bordered">
            <tr>
                <th>No</th>
                <th>Nome do Paciente</th>
                <th>Data</th>
                <th>Hora</th>
                <th>Status</th>
                <th width="280px">Ação</th>
            </tr>
            
            @foreach ($consultas as $consulta)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $consulta->paciente_nome }}</td>
                    <td>{{ \Carbon\Carbon::parse($consulta->data)->format('d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($consulta->hora)->format('H:i') }}</td>
                    <td>{{ $consulta->status }}</td>
                    <td>
                        <form action="{{ route('consultas.destroy', $consulta->id) }}" method="POST"> 
                            <!-- $consultaTotal -->
                            <a class="btn btn-primary" href="{{ route('consultas.edit', $consulta->id) }}">Editar</a>
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Deletar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
    @else
        <div class="alert alert-info">
            <p>Não há consultas agendadas.</p>
        </div>
    @endif
</div>
@endsection
