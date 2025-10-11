@extends('base')
@section('content')
<style>
    .card {
        background-color: #f1f1f1;
        box-shadow: 0 5px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }
</style>
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
                                    <input type="text" name="nome" value="{{ $bloco->nome }}" class="form-control d-none w-50" id="name-input-{{ $bloco->id }}">

                                <button
                                    type="button"
                                    class="btn btn-warning btn-sm bi bi-pencil-square shadow-sm"
                                    id="edit-btn-{{ $bloco->id }}"
                                    onclick="enableEdit({{ $bloco->id }})"
                                ></button>

                                <button
                                    type="button"
                                    class="btn btn-danger btn-sm bi bi-x m-1 shadow-sm d-none"
                                    id="cancel-btn-{{ $bloco->id }}"
                                    onclick="cancelEdit({{ $bloco->id }})"
                                    title="Cancelar edição"
                                ></button>

                                </h5>
                                <p class="card-text">Torres: {{ $bloco->qtdTorres ?? '0' }}</p>
                            </form>

                            <!-- Form de deletar separado -->
                            <form
                                action="{{ route('blocos.delete', $bloco->id) }}"
                                method="POST"
                                class="d-inline"
                                onsubmit="return confirm('Tem certeza que deseja deletar este bloco?')"
                            >
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm mt-3 bi bi-trash"></button>
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
        const editBtn = document.getElementById(`edit-btn-${id}`);
        const cancelBtn = document.getElementById(`cancel-btn-${id}`);
        const form = document.getElementById(`form-${id}`);

        input.classList.remove('d-none');
        display.classList.add('d-none');

    editBtn.classList.remove('btn-warning', 'bi-pencil-square');
        editBtn.classList.add('btn-success', 'bi-check-lg');

        editBtn.onclick = function() { form.submit(); };

        cancelBtn.classList.remove('d-none');

        input.focus();
        input.select();

        input.onkeydown = function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                form.submit();
            } else if (e.key === 'Escape') {
                cancelEdit(id);
            }
        };
    }

    function cancelEdit(id) {
        const input = document.getElementById(`name-input-${id}`);
        const display = document.getElementById(`name-display-${id}`);
        const editBtn = document.getElementById(`edit-btn-${id}`);
        const cancelBtn = document.getElementById(`cancel-btn-${id}`);

        input.value = display.textContent.trim();

        input.classList.add('d-none');
        display.classList.remove('d-none');

        editBtn.classList.remove('btn-success', 'bi-check-lg');
        editBtn.classList.add('btn-info', 'bi-pencil-square');

        editBtn.onclick = function() { enableEdit(id); };

        cancelBtn.classList.add('d-none');

        input.onkeydown = null;
    }
</script>
@endsection
