@extends('layouts.base')
@section('content')

<style>
  .apartamento-item {
    transition: background-color 0.3s ease;
    margin-bottom: 0.75rem;
    border-radius: 8px;
    border: 1px solid #e9ecef !important;
  }
  .info-destaque {
    font-size: 0.95rem;
    font-weight: 600;
    color: #2c3e50;
    line-height: 1.2;
  }
  .info-secundaria {
    font-size: 0.8rem;
    color: #6c757d;
    line-height: 1.3;
  }
  .list-group-item {
    padding: 0.75rem 1rem;
  }
</style>

<div class="container">
    <h2 class="mb-4">Apartamentos</h2>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('apartamentos.create') }}" class="btn btn-primary">Novo Apartamento</a>
        <x-count 
            :total="$apartamentos->count()" 
            label="Total:" 
        />
    </div>

    <x-search
        :action="route('apartamentos.search')" 
        placeholder="Buscar apartamento..."
    />

    <ul class="list-group">
        @foreach($apartamentos as $apartamento)
            <li class="list-group-item apartamento-item" id="apartamento-item-{{ $apartamento->id }}">
                <div class="row g-2 align-items-center">
                    <div class="col-md-10">
                        <form id="form-apt-{{ $apartamento->id }}" action="{{ route('apartamentos.edit', $apartamento->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row g-2">
                                <!-- Número do Apartamento -->
                                <div class="col-md-6">
                                    <div class="mb-1">
                                        <i class="bi bi-door-closed text-primary me-1"></i>
                                        <span class="info-destaque" id="numero-display-{{ $apartamento->id }}">Apt {{ $apartamento->numero }}</span>
                                        <input type="text" name="numero" value="{{ $apartamento->numero }}" class="form-control form-control-sm d-none" id="numero-input-{{ $apartamento->id }}" maxlength="10">
                                    </div>
                                    <div class="info-secundaria">
                                        <i class="bi bi-building me-1"></i>
                                        <span id="torre-display-{{ $apartamento->id }}">{{ $apartamento->torre->nome ?? '—' }}</span>
                                        @if(optional($apartamento->torre)->bloco)
                                          | {{ $apartamento->torre->bloco->nome }}
                                        @endif
                                        <select name="torre_id" id="torre-input-{{ $apartamento->id }}" class="form-select form-select-sm d-none">
                                            <option value="">Selecione uma torre</option>
                                            @isset($torres)
                                                @foreach($torres as $torre)
                                                    <option value="{{ $torre->id }}" {{ (string)$apartamento->torre_id === (string)$torre->id ? 'selected' : '' }}>
                                                        {{ $torre->nome ?? "Torre #{$torre->id}" }}
                                                    </option>
                                                @endforeach
                                            @endisset
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="col-md-2 text-end">
                        <button
                            type="button"
                            class="btn btn-warning btn-sm bi bi-pencil-square shadow-sm me-1"
                            id="edit-btn-apt-{{ $apartamento->id }}"
                            onclick="enableEditApt({{ $apartamento->id }})"
                            title="Editar"
                        ></button>

                        <button
                            type="button"
                            class="btn btn-secondary btn-sm bi bi-x shadow-sm d-none"
                            id="cancel-btn-apt-{{ $apartamento->id }}"
                            onclick="cancelEditApt({{ $apartamento->id }})"
                            title="Cancelar edição"
                        ></button>

                        <form
                            action="{{ route('apartamentos.delete', $apartamento->id) }}"
                            method="POST"
                            class="d-inline"
                            onsubmit="return confirm('Tem certeza que deseja excluir este apartamento?')"
                        >
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm bi bi-trash" title="Excluir"></button>
                        </form>
                    </div>
                </div>
            </li>
        @endforeach
    </ul>

    <div class="mt-3">
        <x-pagination :paginator="$apartamentos" :summary="false" align="center" />
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