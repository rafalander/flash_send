@extends('base')
@section('content')
<div class="container">
    <h2 class="mb-4">Apartamentos</h2>

    <a href="{{ route('apartamentos.create') }}" class="btn btn-primary mb-3">Novo Apartamento</a>

    <ul class="list-group">
        @foreach($apartamentos as $apartamento)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center flex-grow-1 gap-2">
                    <form id="form-apt-{{ $apartamento->id }}" action="{{ route('apartamentos.edit', $apartamento->id) }}" method="POST" class="d-flex align-items-center flex-grow-1 gap-2">
                        @csrf
                        @method('PUT')

                        <span class="text-display me-2 fw-bold" id="numero-display-{{ $apartamento->id }}">{{ $apartamento->numero }}</span>
                        <input type="text" name="numero" value="{{ $apartamento->numero }}" class="form-control d-none w-auto me-2" id="numero-input-{{ $apartamento->id }}" maxlength="10">

                        <span class="text-muted small" id="torre-display-{{ $apartamento->id }}">{{ $apartamento->torre->nome ?? '—' }}</span>
                        <select name="torre_id" id="torre-input-{{ $apartamento->id }}" class="form-select form-select-sm d-none w-auto">
                            <option value="">Selecione uma torre</option>
                            @isset($torres)
                                @foreach($torres as $torre)
                                    <option value="{{ $torre->id }}" {{ (string)$apartamento->torre_id === (string)$torre->id ? 'selected' : '' }}>
                                        {{ $torre->nome ?? "Torre #{$torre->id}" }}
                                    </option>
                                @endforeach
                            @endisset
                        </select>

                        <span class="text-muted small">| {{ optional(optional($apartamento->torre)->bloco)->nome ?? '—' }}</span>
                    </form>
                </div>

                <div class="d-flex align-items-center">
                    <button
                        type="button"
                        class="btn btn-warning btn-sm bi bi-pencil-square shadow-sm me-1"
                        id="edit-btn-apt-{{ $apartamento->id }}"
                        onclick="enableEditApt({{ $apartamento->id }})"
                        title="Editar"
                    ></button>

                    <button
                        type="button"
                        class="btn btn-secondary btn-sm bi bi-x m-1 shadow-sm d-none"
                        id="cancel-btn-apt-{{ $apartamento->id }}"
                        onclick="cancelEditApt({{ $apartamento->id }})"
                        title="Cancelar edição"
                    ></button>

                    <form
                        action="{{ route('apartamentos.delete', $apartamento->id) }}"
                        method="POST"
                        class="d-inline ms-2"
                        onsubmit="return confirm('Tem certeza que deseja excluir este apartamento?')"
                    >
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm bi bi-trash" title="Excluir"></button>
                    </form>
                </div>
            </li>
        @endforeach
    </ul>

    <div class="d-flex justify-content-center mt-3">
        {{ $apartamentos->links() }}
    </div>

    <script>
        function enableEditApt(id) {
            const numeroInput = document.getElementById(`numero-input-${id}`);
            const numeroDisplay = document.getElementById(`numero-display-${id}`);
            const torreDisplay = document.getElementById(`torre-display-${id}`);
            const torreInput = document.getElementById(`torre-input-${id}`);
            const editBtn = document.getElementById(`edit-btn-apt-${id}`);
            const cancelBtn = document.getElementById(`cancel-btn-apt-${id}`);
            const form = document.getElementById(`form-apt-${id}`);

            if (!numeroInput || !numeroDisplay || !editBtn || !cancelBtn || !form) return;

            numeroInput.classList.remove('d-none');
            numeroDisplay.classList.add('d-none');
            if (torreDisplay && torreInput) {
                torreDisplay.classList.add('d-none');
                torreInput.classList.remove('d-none');
            }

            editBtn.classList.remove('btn-warning', 'bi-pencil-square');
            editBtn.classList.add('btn-success', 'bi-check-lg');
            editBtn.title = 'Salvar';

            editBtn.onclick = function() { form.submit(); };

            cancelBtn.classList.remove('d-none');

            numeroInput.focus();
            numeroInput.select();

            numeroInput.onkeydown = function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    form.submit();
                } else if (e.key === 'Escape') {
                    cancelEditApt(id);
                }
            };
        }

        function cancelEditApt(id) {
            const numeroInput = document.getElementById(`numero-input-${id}`);
            const numeroDisplay = document.getElementById(`numero-display-${id}`);
            const torreDisplay = document.getElementById(`torre-display-${id}`);
            const torreInput = document.getElementById(`torre-input-${id}`);
            const editBtn = document.getElementById(`edit-btn-apt-${id}`);
            const cancelBtn = document.getElementById(`cancel-btn-apt-${id}`);
            const form = document.getElementById(`form-apt-${id}`);

            if (!numeroInput || !numeroDisplay || !editBtn || !cancelBtn || !form) return;

            // reset input value to original
            numeroInput.value = numeroDisplay.textContent.trim();

            numeroInput.classList.add('d-none');
            numeroDisplay.classList.remove('d-none');
            if (torreDisplay && torreInput) {
                torreInput.classList.add('d-none');
                torreDisplay.classList.remove('d-none');
            }

            editBtn.classList.remove('btn-success', 'bi-check-lg');
            editBtn.classList.add('btn-warning', 'bi-pencil-square');
            editBtn.title = 'Editar';

            editBtn.onclick = function() { enableEditApt(id); };

            cancelBtn.classList.add('d-none');

            numeroInput.onkeydown = null;
        }
    </script>
</div>
@endsection