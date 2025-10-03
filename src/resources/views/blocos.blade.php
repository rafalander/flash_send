@extends('base')
@section('content')
<div class="container">
    <h2 class="mb-4">Blocos do Condomínio</h2>
    <a href="{{ route('blocos.create') }}" class="btn btn-primary mb-3">Novo Bloco</a>

    <div class="row">
        @foreach($blocos as $bloco)
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-body">
                        <form id="form-{{ $bloco->id }}" action="{{ route('blocos.edit', $bloco->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <h5 class="card-title">
                                    <span class="text-display" id="name-display-{{ $bloco->id }}">{{ $bloco->nome }}</span>
                                    <input type="text" name="nome" value="{{ $bloco->nome }}" class="form-control d-none" id="name-input-{{ $bloco->id }}">
                                </h5>
                                <p class="card-text">Torres: {{ $bloco->qtdTorres ?? '0' }}</p>
                                <p class="card-text">Apartamentos: {{ $bloco->qtdApartamentos ?? '0' }}</p>
                                <p class="card-text">{{ $bloco->descricao }}</p>

                                <!-- Botão de editar / salvar -->
                                <button
                                    type="button"
                                    class="btn btn-secondary btn-sm bi bi-pencil-square"
                                    id="edit-btn-{{ $bloco->id }}"
                                    onclick="enableEdit({{ $bloco->id }})"
                                ></button>
                            </form>

                            <!-- Form de deletar separado -->
                            <form
                                action="{{ route('blocos.delete', $bloco->id) }}"
                                method="POST"
                                class="d-inline"
                            >
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm bi bi-trash"></button>
                            </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<script>
    function enableEdit(id) {
        const input = document.getElementById(`name-input-${id}`);
        const display = document.getElementById(`name-display-${id}`);
        const btn = document.getElementById(`edit-btn-${id}`);
        const form = document.getElementById(`form-${id}`);

        // Mostrar input e esconder texto
        input.classList.remove('d-none');
        display.classList.add('d-none');

        // Trocar ícone do botão para check e mudar ação
        btn.classList.remove('bi-pencil-square');
        btn.classList.add('btn-success', 'bi-check-lg');
        btn.onclick = function() { form.submit(); };
    }
</script>
@endsection
