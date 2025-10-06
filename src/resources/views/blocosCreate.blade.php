@extends('base')
@section('content')
    <div class="container">
        <h2 class="mb-4">Cadastro de blocos</h2>
        <form action="{{ route('blocos.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="nome" class="form-label">Nome do Bloco</label>
                <input type="text" class="form-control w-25" id="nome" name="nome" required>
            </div>
            <button type="submit" class="btn btn-primary">Salvar</button>
            <a href="{{ route('blocos.index') }}" class="btn btn-danger">Cancelar</a>
    </div>
@endsection