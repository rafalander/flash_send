@extends('base')
@section('content')
<div class="container mt-4">
    <div class="card col-md-8 mx-auto">
        <div class="card-header">
            <h5 class="mb-0">Cadastro de Bloco</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('blocos.store') }}" method="POST" novalidate>
                @csrf

                <div class="mb-3 w-50">
                    <label for="nome" class="form-label">Nome do Bloco</label>
                    <input
                        type="text"
                        name="nome"
                        id="nome"
                        class="form-control @error('nome') is-invalid @enderror"
                        value="{{ old('nome') }}"
                        required
                        maxlength="255"
                        placeholder="Digite o nome do bloco"
                    >
                    @error('nome')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Salvar</button>
                    <a href="{{ route('blocos.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection