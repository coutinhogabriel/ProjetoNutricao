@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Editar Consulta</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('consultas.update', $consulta->id) }}" method="POST">
        @csrf
        @method('PUT')

         <form action="{{ route('consultas.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="paciente_nome">Nome do Paciente</label>
            <input type="text" class="form-control" id="paciente_nome" name="paciente_nome" value="{{ old('paciente_nome') }}" required>
        </div>

        <div class="form-group">
            <label for="data">Data</label>
            <input type="date" class="form-control" id="data" name="data" value="{{ old('data') }}" required>
        </div>

        <div class="form-group">
            <label for="hora">Hora</label>
            <input type="time" class="form-control" id="hora" name="hora" value="{{ old('hora') }}" required>
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select class="form-control" id="status" name="status" required>
                <option value="agendado" {{ old('status') == 'agendado' ? 'selected' : '' }}>Agendado</option>
                <!-- <option value="realizado" {{ old('status') == 'realizado' ? 'selected' : '' }}>Realizado</option>
                <option value="cancelado" {{ old('status') == 'cancelado' ? 'selected' : '' }}>Cancelado</option> -->
            </select>
        </div>

        <button type="submit" class="btn btn-success mt-3">Salvar Consulta</button>
        <a href="{{ route('consultas.index') }}" class="btn btn-secondary mt-3">Voltar</a>
    </form>
</div>
        <a href="{{ route('consultas.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
