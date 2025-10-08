@extends('base')
@section('content')
<div class="container mt-4">
    <div class="card col-md-8 mx-auto">
        <div class="card-header">
            <h5 class="mb-0">Cadastrar Torre</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('torres.store') }}" method="POST" novalidate>
                @csrf

                <div class="mb-3">
                    <label for="nome" class="form-label">Nome da Torre</label>
                    <input
                        type="text"
                        name="nome"
                        id="nome"
                        class="form-control @error('nome') is-invalid @enderror"
                        value="{{ old('nome') }}"
                        required
                        maxlength="255"
                        placeholder="Digite o nome da torre"
                    >
                    @error('nome')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="bloco_id" class="form-label">Bloco</label>
                    <select
                        name="bloco_id"
                        id="bloco_id"
                        class="form-select @error('bloco_id') is-invalid @enderror"
                        required
                        @if($blocos->isEmpty()) disabled @endif
                    >
                        <option value="">Selecione um bloco</option>
                        @forelse($blocos as $bloco)
                            <option value="{{ $bloco->id }}" {{ (string)old('bloco_id') === (string)$bloco->id ? 'selected' : '' }}>
                                {{ $bloco->nome ?? "Bloco #{$bloco->id}" }}
                            </option>
                        @empty
                            <option value="" disabled>Nenhum bloco disponível</option>
                        @endforelse
                    </select>
                    @error('bloco_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    @if($blocos->isEmpty())
                        <div class="alert alert-warning mt-2" role="alert">
                            Nenhum bloco cadastrado. <a href="{{ route('blocos.create') }}">Cadastre um bloco</a> antes de criar torres.
                        </div>
                    @endif
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary" @if($blocos->isEmpty()) disabled title="Cadastre um bloco primeiro" @endif>Salvar</button>
                    <a href="{{ route('torres.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection